<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class CustomerTest extends TestCase
{

    public function testOneToOne(): void
    {
        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class
        ]);

        $customer = Customer::find("LYN");
        self::assertNotNull($customer);

        $wallet = $customer->wallet;
        assertNotNull($wallet);

        self::assertEquals(1000000, $wallet->amount);
    }

    public function testOneToOneInsert()
    {
        $customer = new Customer();
        $customer->id = "LYN";
        $customer->name = "Lyn";
        $customer->email = "lyn@gmail.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;
        $customer->wallet()->save($wallet);

        assertNotNull($wallet->customer_id);
    }


}
