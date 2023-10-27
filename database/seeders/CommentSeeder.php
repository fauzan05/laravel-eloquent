<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::first();
        $comment = new Comment();
        $comment->email = "fauzan@email.com";
        $comment->comment = "Ini adalah komentar untuk produk 1";
        $comment->commentable_id = $product->id;
        $comment->commentable_type = 'product';
        $comment->save();

        $voucher = Voucher::first();
        $comment = new Comment();
        $comment->email = "fauzan@email.com";
        $comment->title = "Voucher 1";
        $comment->comment = "Ini adalah komentar untuk voucher";
        $comment->commentable_id = $voucher->id;
        $comment->commentable_type = 'voucher';
        $comment->save();
    }
}
