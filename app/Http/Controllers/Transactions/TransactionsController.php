<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transactions\Requests\TransactionsBillIdRequest;
use App\Http\Controllers\Transactions\Requests\TransactionsGenerateReport;
use App\Http\Controllers\Transactions\Requests\TransactionsStoreRequest;
use App\Models\Bill;
use App\Models\Transaction;
use PDF;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Protects this controller with middleware
     * Only these users which have right permission can call methods inside this class
     */
    public function __construct()
    {
        $this->middleware('permission:manage-bills');
    }

    /**
     * Getting all transactions (by bill)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $bills = array_map(function($el) {
            return "$el[formatted_number] ($el[balance] $el[currency])";
        }, auth()->user()->bills->keyBy('id')->toArray());

        $bill_id = $request->bill_id ?? array_keys($bills)[0];
        $transactions = Transaction::where(function ($q) use ($bill_id) {
                $q->where('source_bill_id', $bill_id)
                    ->orWhere('target_bill_id', $bill_id);
            })
            ->paginate(15);
        return view('transactions.index', compact(
            'bills',
            'bill_id',
            'transactions'
        ));
    }

    /**
     * Getting the transaction make dialog
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(TransactionsBillIdRequest $request)
    {
        $bill_id = $request->validated()['bill_id'];
        return view('transactions.modals.create', compact('bill_id'));
    }

    /**
     * Making new transaction
     * @param TransactionsStoreRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(TransactionsStoreRequest $request)
    {
        $validated = $request->validated();
        $bill = Bill::findOrFail($validated['bill_id']);
        if ($validated['amount'] > $bill->balance) {
            return response()->json([
                'status' => 400,
                'error' =>
                    __('There is not enough money in this bill')
                    . " ($bill[balance] $bill[currency])"
            ], 400);
        }

        $target_bill_id = Bill::where('number', $validated['target_bill_number'])->first()->id;
        $transaction = new Transaction();
        $transaction->type_id = 1;
        $transaction->source_bill_id = $validated['bill_id'];
        $transaction->target_bill_id = $target_bill_id;
        $transaction->amount = $validated['amount'];
        $transaction->save();
        return response()->json(['status' => 200], 200);
    }

    public function prepareReport(TransactionsBillIdRequest $request)
    {
        $validated = $request->validated();

        $bill = Bill::findOrFail($validated['bill_id']);
        $transactions = Transaction::where(function($q) use($validated) {
                $q->whereSourceBillId($validated['bill_id'])
                    ->orWhere('target_bill_id', $validated['bill_id']);
            });

        $min_date = Date('Y-m-d', strtotime(
            (clone $transactions)->min('created_at'))
        );
        $max_date = Date('Y-m-d', strtotime(
            (clone $transactions)->max('created_at'))
        );

        return view('transactions.modals.prepare-report', compact(
            'bill',
            'min_date',
            'max_date'
        ));
    }

    public function generateReport(TransactionsGenerateReport $request)
    {
        $validated = $request->validated();

        $bill = Bill::findOrFail($validated['bill_id']);
        $transactions = Transaction::with('sourceBill.users', 'targetBill.users')
            ->where(function($q) use($validated) {
                $q->whereSourceBillId($validated['bill_id'])
                    ->orWhere('target_bill_id', $validated['bill_id']);
            })
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->get();

        $data = $validated;
        $pdf = PDF::loadView('pdf.transactions-report', compact(
            'data',
            'bill',
            'transactions'
        ));
        return $pdf->download("transactions_from_{$validated['start_date']}_to_{$validated['end_date']}.pdf");
    }
}
