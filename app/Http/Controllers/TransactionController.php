<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();

        try {
            
            $transaction = Transaction::create([
                'type' => $request->type,
                'amount' => $request->amount,
                'status' => $request->status,
                'user_id' => $request->auth()->user()->id,  // Sender
                'receiver_id' => $request->receiver_id,     // Receiver
                'created_at' => now(),
            ]);
    
            // Deduct amount from the sender's balance
            $sender = User::find($request->auth()->user()->id);
            $sender->balance -= $request->amount;
            $sender->save();
    
            if($request->receiver_id!= null){
                $receiver = User::find($request->receiver_id);
                $receiver->balance += $request->amount;
                $receiver->save();
            }
        
    
            // Commit the transaction
            DB::commit();
    
            return response()->json(['message' => 'Transaction successful'], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if anything fails
            DB::rollBack();
    
            // Handle the error (you can log or return an error response)
            return response()->json(['message' => 'Transaction failed', 'error' => $e->getMessage()], 500);
        }
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
