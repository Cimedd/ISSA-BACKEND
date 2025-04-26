<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            ['user_id' => 1, 'contact_id' => 2],
            ['user_id' => 1, 'contact_id' => 3],
            ['user_id' => 2, 'contact_id' => 1],
            ['user_id' => 2, 'contact_id' => 4],
            ['user_id' => 3, 'contact_id' => 1],
        ];

        // Insert into 'contacts' table
        DB::table('contacts')->insert($contacts);
    }
}
