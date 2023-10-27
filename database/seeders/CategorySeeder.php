<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = new Category();
        $category->id = "001";
        $category->name = "Gadget";
        $category->description = "Gadget Category";
        $category->is_active = true;
        $category->save();
        $category = new Category();
        $category->id = "002";
        $category->name = "Makanan";
        $category->description = "Makanan Category";
        $category->is_active = true;
        $category->save();
    }
}
