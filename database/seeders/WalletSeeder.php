<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{

    public function run(): void
    {
        $wallet = new Wallet();
        $wallet->customer_id = "LYN";
        $wallet->amount = 1000000;
        $wallet->save();
    }
}
