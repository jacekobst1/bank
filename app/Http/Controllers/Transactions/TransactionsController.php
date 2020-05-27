<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transactions\Requests\TransactionsCreateRequest;
use App\Http\Controllers\Transactions\Requests\TransactionsStoreRequest;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class TransactionsController extends Controller
{
    /**
     * Protects this controller with middleware
     * Only these users which have right permission can call methods inside this class
     */
    public function __construct()
    {
        $this->middleware('permission:manage-bill');
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
        }, auth()->user()->bills()->get()->keyBy('id')->toArray());

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
    public function create(TransactionsCreateRequest $request)
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
        return response()->json([], 200);
    }
}
