<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $review = new Review();
        $review->product_id = "1";
        $review->customer_id = "001";
        $review->rating = 5;
        $review->comment = "Produknya bagus banget";
        $review->save();

        $review = new Review();
        $review->product_id = "1";
        $review->customer_id = "001";
        $review->rating = 4;
        $review->comment = "Produknya biasa aja";
        $review->save();

    }
}
