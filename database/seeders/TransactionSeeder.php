<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $transactions = [
            ['type' => 'deposit', 'amount' => 50000, 'status' => 'success', 'user_id' => 1],
            ['type' => 'transfer', 'amount' => 100000, 'status' => 'pending', 'user_id' => 2],
            ['type' => 'withdraw', 'amount' => 20000, 'status' => 'success', 'user_id' => 3],
            ['type' => 'topup', 'amount' => 300000, 'status' => 'success', 'user_id' => 1],
            ['type' => 'billing', 'amount' => 100000, 'status' => 'pending', 'user_id' => 1],
        ];
        
        DB::table('transactions')->insert($transactions);
    }
}
