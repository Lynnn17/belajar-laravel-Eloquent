<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertCount;
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
}

