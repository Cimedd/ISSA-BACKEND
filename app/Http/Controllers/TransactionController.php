<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully fetched',
            'transactions' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'user_id' => $transaction->user_id,
                    'receiver_id' => $transaction->receiver_id,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                    'details' => $transaction->details->details,  // Only include the 'details' field from the related 'details' table
                ];
            }),
        ]);
    }

    public function getTransactionByStatus(Request $request){

        if (Auth::user()->role === 'admin') {
            $transactions = Transaction::with('details')->where('status', $request->filter)->get();
        } else {
            $transactions = Transaction::with('details')
                ->where('status', $request->filter)
                ->where('user_id', Auth::id())
                ->get();
        }
        return response()->json(['status' => 'success','message' => 'Data successfully fetched', 'transactions' => $transactions]);
    }

    public function getTransactionByType(Request $request){
        if (Auth::user()->role === 'admin') {
            $transactions = Transaction::with('details')->where('type', $request->filter)->get();
        } else {
            $transactions = Transaction::with('details')
                ->where('type', $request->filter)
                ->where('user_id', Auth::id())
                ->get();
        }
        return response()->json(['status' => 'success','message' => 'Data successfully fetched', 'transactions' => $transactions]);
    }

    public function insert(Request $request){

        if($request->pin !== null){
            $user = auth()->user();
            if (!Hash::check($request->pin, $user->pin)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid PIN'
                ], 401);
            }
        }
     
        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'type' => $request->type,
                'amount' => $request->amount,
                'status' => $request->status,
                'user_id' => auth()->user()->id,  // Sender
                'receiver_id' => $request->receiver_id,     // Receiver
                'created_at' => now(),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'details' => $request->details, 
            ]);
    
            // Deduct amount from the sender's balance
            $sender = User::find(auth()->user()->id);
            if($request->type == "deposit"){
                $sender->saldo += $request->amount;
            }
            else{
                $sender->saldo -= $request->amount;
            }
           
            $sender->save();
    
            if($request->receiver_id!= null){
                $receiver = User::find($request->receiver_id);
                $receiver->saldo += $request->amount;
                $receiver->save();
            }

            
            // Commit the transaction
            DB::commit();
    
            return response()->json(['status' => 'success','message' => 'Transaction successful'], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if anything fails
            DB::rollBack();
    
            // Handle the error (you can log or return an error response)
            return response()->json(['status' => 'success', 'message' => 'Transaction failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['status' => 'success', 'message' => 'Transaction not found'], 404);
        }

        $transaction->update($request->all());
        return response()->json($transaction);
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['status' => 'success', 'message' => 'Transaction not found'], 404);
        }

        $transaction->delete();
        return response()->json(['status' => 'success', 'message' => 'Transaction deleted']);
    }

    
}
