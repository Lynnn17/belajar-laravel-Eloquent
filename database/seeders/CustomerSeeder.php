<?php

namespace Database\Seeders;


use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = new Customer();
        $customer->id = "LYN";
        $customer->name = "Lyn";
        $customer->email = "Lyn@gmail.com";
        $customer->save();

    }
}
