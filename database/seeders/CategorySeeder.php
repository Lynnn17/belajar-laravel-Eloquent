<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run(): void
    {
       $category = new Category();
       $category->id = "FOOD";
       $category->name = "Food";
       $category->description = "Food Category";
       $category->save();
    }
}
