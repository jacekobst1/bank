<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Bill;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, Bill $bill)
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
        return view('home', compact(
            'bills',
            'bill_id',
            'transactions',
            $bill
        ));
    }
}
