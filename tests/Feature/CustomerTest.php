<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
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

    public function testHasOneThrough()
    {
        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class,
            VirtualAccountSeeder::class
        ]);

        $customer = Customer::find("LYN");
        assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        assertNotNull($virtualAccount);
        assertEquals("BRI", $virtualAccount->bank);
    }

    public function testManyToMany()
    {
        $this->seed([
            CustomerSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $customer = Customer::find("LYN");
        assertNotNull($customer);

        //method attach() untuk menambahkan/insert relasi
        $customer->likeProducts()->attach("1");

        $products = $customer->likeProducts;
        self::assertCount(1, $products);

        assertEquals("1", $products[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        $customer = Customer::find("LYN");
        assertNotNull($customer);

        //method attach() untuk menghapus/delete relasi
        $customer->likeProducts()->detach("1");

        $products = $customer->likeProducts;
        self::assertCount(0, $products);


    }

    public function testPivotAttribute()
    {
        $this->testManyToMany();

        $customer = Customer::find("LYN");
        $products = $customer->likeProducts;

        foreach ($products as $product)
        {
            $pivot = $product->pivot;
            assertNotNull($pivot);
            assertNotNull($pivot->customer_id);
            assertNotNull($pivot->product_id);
            assertNotNull($pivot->created_at);
        }
    }

    public function testPivotAttributeCondition()
    {
        $this->testManyToMany();

        $customer = Customer::find("LYN");
        $products = $customer->likeProductsLastWeek;

        foreach ($products as $product)
        {
            $pivot = $product->pivot;
            assertNotNull($pivot);
            assertNotNull($pivot->customer_id);
            assertNotNull($pivot->product_id);
            assertNotNull($pivot->created_at);
        }
    }


}
