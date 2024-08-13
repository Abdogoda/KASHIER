<?php

namespace App\Http\Controllers;

use App\Models\FundAccount;
use App\Models\Payment;
use Illuminate\Http\Request;

class SafeController extends Controller
{
    public function __construct(){
        $this->middleware('permission:safe')->only('index');
    }

    public function index(Request $request){
        $balance = FundAccount::first()->balance;

        $query = Payment::query();
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $transactions = $query->orderBy('created_at', 'desc')->get()->take(50);

        return view('pages.safe', compact('balance', 'transactions'));
    }
    
    
}