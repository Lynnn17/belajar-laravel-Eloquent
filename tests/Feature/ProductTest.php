<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testOneToMany(): void
    {
      $this->seed([
          CategorySeeder::class,
          ProductSeeder::class
      ]);

      $product = Product::find("1");
      self::assertNotNull($product);

      $category = $product->category;
      self::assertEquals("FOOD", $category->id);
    }

    //menambil 1 data
    public function testHasOneOfMany()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $category = Category::find("FOOD");
        assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        self::assertNotNull($cheapestProduct);
        self::assertEquals(1,$cheapestProduct->id);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpensiveProduct);
        self::assertEquals(2,$mostExpensiveProduct->id);

    }

    public function testEloquentCollection()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // 2 product 1,2
        $product = Product::query()->get();

        //toQuery sama dengan WHERE id in (1,2) yang berasal nilainya berasal
        // dari $product
        $query = $product->toQuery()->where('price','=',200000)->get();
        assertNotNull($query);

        assertEquals("1", $product[0]->id);

    }

    //convert ke json
    public function testSerialization()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $product = Product::query()->get();
        assertCount(2, $product);

        $json = $product->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }

    // untuk menampilkan data yang ada di relasi tabel
    public function testSerializationRelation()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $product = Product::query()->get();
        $product->load("category");
        assertCount(2, $product);

        $json = $product->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }

}

