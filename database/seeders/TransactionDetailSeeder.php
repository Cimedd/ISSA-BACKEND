<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;

class TransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $transactionDetails = [
            [
                'details' => 'Details for transaction 1',
                'transaction_id' =>1,
            ],
            [
                'details' => 'Details for transaction 2',
                'transaction_id' => 2,
            ],
            [
                'details' => 'Details for transaction 3',
                'transaction_id' =>3,
            ],
            [
                'details' => 'Details for transaction 4',
                'transaction_id' => 4,
            ],
            [
                'details' => 'Details for transaction 5',
                'transaction_id' => 5,
            ],
        ];

        TransactionDetail::insert($transactionDetails);
    }
}
