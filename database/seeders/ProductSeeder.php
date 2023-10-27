<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->id = '1';
        $product->name = 'Samsung Galaxy S7';
        $product->description = 'This is an Galaxy S7 new';
        $product->price = 10000000;
        $product->category_id = '001';
        $product->save();

        $product = new Product();
        $product->id = '2';
        $product->name = 'Samsung Galaxy S6';
        $product->description = 'This is an Galaxy S6 new';
        $product->price = 4000000;
        $product->category_id = '001';
        $product->save();
    }
}
