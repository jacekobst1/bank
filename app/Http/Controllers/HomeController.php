<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:manage-bills');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bills = array_map(function($el) {
            return "$el[formatted_number] ($el[balance] $el[currency])";
        }, auth()->user()->bills->keyBy('id')->toArray());
        $cards = auth()->user()->cards;
        return view('home', compact(
            'bills',
            'cards'
        ));
    }
}
