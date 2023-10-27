<?php

namespace Tests\Feature;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testCreateComment()
    {
        $comment = new Comment();
        $comment->email = 'name@email.com';
        $comment->title = 'Sample Title';
        $comment->comment = 'Sample Comment';
        $comment->created_at = new \DateTime();
        $comment->updated_at = new \DateTime();
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';
        $comment->save();

        self::assertNotNull($comment->id);
        self::assertNotNull($comment->title);
        self::assertNotNull($comment->comment);
    }

    public function testCreateByRequest()
    {
        $request = [
            'id' => '001',
            'name' => 'Food',
            'description' => 'Food Category'
        ];
        $category = new Category($request);
        $category->save();

        self::assertNotNull($category->id); 
        self::assertNotNull($category->name); 
        self::assertNotNull($category->description); 
    }
    public function testCreateMethod()
    {
        $request = [
            'id' => '001',
            'name' => 'Food',
            'description' => 'Food Category'
        ];
        $category = Category::create($request);

        self::assertNotNull($category->id); 
        self::assertNotNull($category->name); 
        self::assertNotNull($category->description); 
    }

    public function testSoftDeletes()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::where('name', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::where('name', 'Sample Voucher')->first();
        // var_dump($voucher);
        self::assertEquals(null, $voucher);
    }
}
