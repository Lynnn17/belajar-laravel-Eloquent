<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class CategoryTest extends TestCase
{
    public function testInsert(): void
    {
        $categories = new Category();
        $categories->id = "GADGET";
        $categories->name = "Gadget";
        $result = $categories -> save();

        self::assertTrue($result);

    }

    public function testInsertMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                'id' => "id $i",
                'name' => "Name $i",
                'is_active' => true
            ];
        }

        $result =  Category::insert($categories);

        self::assertTrue($result);

        $total = Category::count();

        self::assertEquals(10, $total);
    }

    public function testFind() //untuk mencari 1 file saja lebih baik daripada menggunakan select
    {
        $this->seed(CategorySeeder::class);

        //$category = Category::query()->find("FOOD");
        $category = Category::find("FOOD");

        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
        self::assertEquals("Food", $category->name);
        self::assertEquals("Food Category", $category->description);

    }

    public function testUpdate()
    {
        //saat update kita harus melakukan find terlebih dahulu
        // jika pada suatu kasus tertentu kita tidak bisa melakukan find
        // dan terpaksa harus menggunakan kata kunci new, kita harus merubah
        // attribute $exists dari defaultnya false menjadi true, untuk memberitahu
        // laravel bahwa data object itu ada didatabase
        // boleh menggunakan update() atau save()

        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $category->name = "Food Updated";

        $result = $category->update();
        self::assertTrue($result);

    }

    public function testSelect()
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->id = "ID $i";
            $category->name = "Name $i";
            $category->is_active = true;
            $category->save();
        }

        $categories = Category::whereNull("description")->get();
        assertEquals(5,$categories->count());
        $categories->each(function ($category) {
            self::assertNull($category->description);
            //karena hasilnya arraymodel bukan arraycollection jadi bisa ditambah lagi seperti update dll
            $category->description = "Updated";
            $category->update();
        });

    }

    public function testUpdateMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i",
                "is_active" => true
            ];
        }

        $result = Category::insert($categories);
        self::assertTrue($result);

        Category::whereNull("description")->update([
            "description" => "Updated"
        ]);
        $total = Category::where("description", "=", "Updated")->count();
        self::assertEquals(10, $total);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $result = $category->delete();
        self::assertTrue($result);

        $total = Category::count();

        self::assertEquals(0, $total);
    }

    public function testDeleteMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i",
                "is_active" => true
            ];
        }

        $result = Category::insert($categories);
        self::assertTrue($result);

        $total = Category::count();
        assertEquals(10, $total);

        Category::whereNull("description") -> delete();

        $total = Category::count();
        assertEquals(0, $total);
    }

    public function testCreate()
    {
        //contoh dari http request
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];
        $category = new Category($request);
        $category->save();

        self::assertNotNull($category->id);
    }

    public function testCreateUsingQueryBuilder()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];
        $category = Category::create($request);

        self::assertNotNull($category->id);
    }

    public function testUpdateMass()
    {
        $this->seed(CategorySeeder::class);
        $request = [
            "name" => "Food Updated",
            "description" => "Food Category Updated"
        ];
        $category = Category::find("FOOD");
        $category -> fill($request);
        $category->save();
        self::assertNotNull($category->id);
    }

   public function testGlobalScope()
   {
       $category = new Category();
       $category->id = "FOOD";
       $category->name = "Food";
       $category->description = "Food Category";
       $category->is_active = false;
       $category->save();

       $category = Category::find("FOOD");
       self::assertNull($category);

       //cara mematikan globalscope
       $category = Category::withoutGlobalScopes([IsActiveScope::class])->find("FOOD");
       self::assertNotNull($category);

   }

  public function testOneToMany()
  {
    $this->seed([
        CategorySeeder::class,
        ProductSeeder::class
        ]);

    $category = Category::find("FOOD");
    assertNotNull($category);

    $product = $category->products;

    assertNotNull($product);
    self::assertCount(2, $product);

  }

  public function testOneToManyInsert()
  {
      $category = new Category();
      $category->id = "FOOD";
      $category->name = "Food";
      $category->description = "Food Category";
      $category->is_active = true;
      $category->save();

      $product = new Product();
      $product->id = "1";
      $product->name = "Product 1";
      $product->description = "Product 1 Description";

      $category->products()->save($product);

      assertNotNull($product);

  }

  public function testRelationshipQuery()
  {
      $this->seed([
          CategorySeeder::class,
          ProductSeeder::class
          ]);

      $category = Category::find("FOOD");
      $products = $category->products;
      self::assertCount(2, $products);
      $outOfStockProducts =$category->products()->where("stock","<=",0)->get();
      self::assertCount(2, $outOfStockProducts);

  }

    public function testHasManyThrough()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            ReviewSeeder::class
        ]);

        $category = Category::find("FOOD");
        assertNotNull($category);

        $review = $category->review;
        assertNotNull($review);
        assertCount(2, $review);


    }

    public function testQueryingRelations()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $category = Category::find("FOOD");
        $products = $category->products()->where("price","=",200000)->get();
        assertCount(1,$products );
        self::assertEquals("2", $products[0]->id );
    }

    public function testAggregationRelations()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        $category = Category::find("FOOD");
        $total = $category->products()->count();
        self::assertEquals(2, $total );

        $total = $category->products()->where('price',200000)->count();
        self::assertEquals(1, $total );
    }


}
