<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallet = new Wallet();
        $wallet->amount = 6000000;
        $wallet->customer_id = '001';
        $wallet->save();
    }
}
