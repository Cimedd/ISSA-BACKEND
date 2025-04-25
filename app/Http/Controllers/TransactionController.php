<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionController extends Controller
{
    public function getAll(){
        $user = JWTAuth::user();
        $transactions = Transaction::with('details')->get();

        if ($user->role == 'admin') {
            $transactions = Transaction::with('details')->get();
        } else {
            $transactions = Transaction::with('details')
                ->where('user_id', $user->id)  // Filter by user_id for regular users
                ->get();
        }
        return response()->json($transactions);
    }

    public function getTransactionByStatus(Request $request){
        
        $transactions = Transaction::with('details')->where('status', $request->filter)->get();
        if (Auth::user()->role === 'admin') {
            $transactions = Transaction::with('details')->where('status', $request->filter)->get();
        } else {
            $transactions = Transaction::with('details')
                ->where('status', $request->filter)
                ->where('user_id', Auth::id())
                ->get();
        }
        return response()->json($transactions);
    }

    public function getTransactionByType(Request $request){
        $transactions = Transaction::with('details')->where('type', $request->filter)->get();
        if (Auth::user()->role === 'admin') {
            $transactions = Transaction::with('details')->where('type', $request->filter)->get();
        } else {
            $transactions = Transaction::with('details')
                ->where('type', $request->filter)
                ->where('user_id', Auth::id())
                ->get();
        }
        return response()->json($transactions);
    }

    public function insert(Request $request){

        $transaction = Transaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'status' => $request->status,
            'user_id' => $request->auth()->user()->id,  
            'created_at' => $request->time,
        ]
        );
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'details' => $request->details]);
        return response()->json(['message' => 'Transaction deleted successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->update($request->all());
        return response()->json($transaction);
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }

    
}
