<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Person;
use App\Models\Product;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\VoucherSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RelationTest extends TestCase
{
    public function testQueryOneToOne()
    {
        $this->seed(CustomerSeeder::class);
        $this->seed(WalletSeeder::class);

        $customer = Customer::find('001');
        // var_dump($customer->wallet);
        self::assertEquals('001', $customer->id);
        self::assertEquals('fauzan', $customer->name);

        // $wallet = Wallet::where('customer_id', '001')->first();
        $wallet = $customer->wallet;
        self::assertEquals(6000000, $wallet->amount);
    }

    public function testQueryCategory()
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        // $category = Category::get()->all();
        $category = Category::get();
        // var_dump($category[0]->products[1]->name);
        $product = $category[0]->products;
        self::assertEquals('Samsung Galaxy S7', $product[0]->name);
        self::assertEquals('Samsung Galaxy S6', $product[1]->name);
    }

    public function testOneToManyCategory()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('001');
        self::assertCount(2,$category->products);
    }

    public function testOneToManyProducts()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::find('1');
        self::assertEquals('Gadget', $product->category->name);
        $product = Product::find('2');
        self::assertEquals('001', $product->category->id);

    }

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = '1';
        $customer->name = 'Fauzan';
        $customer->email = 'fauzan@mail.com';
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 450000;
        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet->customer_id);
    }

    public function testInsertRelationship()
    {
        $category = new Category();
        $category->id = '1';
        $category->name = 'Gadget';
        $category->description = 'This is an Gadget';
        $category->is_active = true;
        $category->save();

        $find = Category::find($category->id);
        // var_dump($find->name);
        self::assertEquals('Gadget', $find->name);

        $product = new Product();
        $product->id = '1';
        $product->name = 'Samsung Galaxy S10';
        $product->description = 'This is an Galaxy S10';
        $category->products()->save($product);

        $find = Product::find($product->id);
        self::assertEquals('Samsung Galaxy S10', $find->name);
        self::assertEquals($category->id, $find->category_id);
    }

    public function testSearchProduct()
    {
        $this->testInsertRelationship();
        $category = Category::find('1');
        $stockProduct = $category->products()->where('stock', '<=', 0)->get();
        self::assertCount(1, $stockProduct);
        self::assertEquals('Samsung Galaxy S10', $stockProduct->all()[0]->name);
    }

    public function testHasOneOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $category = Category::find('001');
        // var_dump($category);
        $cheapestProduct = $category->cheapestProduct;
        // var_dump($cheapestProduct);
        self::assertNotNull($cheapestProduct);
        self::assertEquals('Samsung Galaxy S6', $cheapestProduct->all()[1]->name);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpensiveProduct);
        self::assertEquals('Samsung Galaxy S7', $mostExpensiveProduct->all()[0]->name);

    }

    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find('001');
        self::assertEquals('001', $customer->id);
        $virtualAccount = $customer->virtualAccount;
        self::assertEquals('BCA', $virtualAccount->bank);
        $wallet = $customer->wallet::where('customer_id', $customer->id)->get();
        // var_dump($wallet->all()[0]->customer_id);
        self::assertEquals($customer->id, $wallet->all()[0]->customer_id);
    }

    public function testHasManyThrough()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);
        $category = Category::find("001");
        self::assertNotNull($category);

        $reviews = $category->reviews;
        self::assertCount(2, $reviews);
    }
    public function testInsertManyToMany()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);
        $customer = Customer::find("001");
        self::assertNotNull($customer);
        $customer->likeProducts()->attach('1');
        $products = $customer->likeProducts;
        // or
        // $products = $customer->likeProducts()->get()->all();
        // var_dump($products[0]->name);
        self::assertCount(1, $products);
    }

    public function testRemoveManyToMany()
    {
        $this->testInsertManyToMany();
        $customer = Customer::find("001");
        $customer->likeProducts()->detach("1");

        $product = $customer->likeProducts;
        self::assertNotNull($product);
        self::assertCount(0, $product);
    }

    public function testPivotAttribute()
    {
        $this->testInsertManyToMany();

        $customer = Customer::find("001");
        $products = $customer->likeProducts;

        foreach($products as $product){
            $pivot  = $product->pivot;
            // var_dump($pivot->customer_id);
            self::assertEquals("001", $pivot->customer_id);
            self::assertEquals("1", $pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotModel()
    {
        $this->testInsertManyToMany();
        $customer = Customer::find("001");
        $products = $customer->likeProducts;
        foreach($products as $product){
            $pivot = $product->pivot;
            // var_dump($pivot->product->name);
            self::assertEquals("Samsung Galaxy S7", $pivot->product->name);
            self::assertEquals("fauzan", $pivot->customer->name);
        }
    }

    public function testOneToOnePolymorphic1()
    {
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);
        $customer = Customer::find("001");
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);
        self::assertEquals("https://www.programmerzamannow.com/images/1.jpg", $image->url);
    }
    public function testOneToOnePolymorphic2()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);
        $product = Product::find("1");
        self::assertNotNull($product);

        $image = $product->image;
        self::assertNotNull($image);
        self::assertEquals("https://www.programmerzamannow.com/images/2.jpg", $image->url);

    }

    public function testOneToManyPolymorphic1()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);
        $product = Product::find("1");
        self::assertNotNull($product);

        $comments = $product->comments;
        foreach($comments as $comment){
            self::assertEquals('product', $comment->commentable_type);
            self::assertEquals($product->id, $comment->commentable_id);
        }
    }

    public function testOneOfManyPolymorphic2()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::first();
        $latestComment = $product->latestComment;
        self::assertNotNull($latestComment);

        $oldestComment = $product->oldestComment;
        self::assertNotNull($oldestComment);
    }

    public function testManyToManyPolymorphic1()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, TagSeeder::class]);
        $product = Product::first();
        $tags = $product->tags;
        self::assertNotNull($tags);
        self::assertCount(1, $tags);

        foreach($tags as $tag){
            self::assertNotNull($tag);
            self::assertNotNull($tag->id);
            self::assertNotNull($tag->name);

            $vouchers = $tag->vouchers;
            self::assertNotNull($vouchers);
            self::assertCount(1, $vouchers);
        }
    }

    public function testEager()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);
        $customer = Customer::with(['wallet', 'image'])->find("001");
        self::assertNotNull($customer);
    }
    
    public function testQueryingRelations()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $category = Category::find("001");
        $products = $category->products()->where('price', '=', '4000000')->get();
        self::assertCount(1, $products);
    }

    public function testQueryingRelationsAggregate()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $category = Category::find('001');
        $totalProduct = $category->products()->count();

        self::assertEquals(2, $totalProduct);
    }

    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Fauzan";
        $person->last_name = "Nur Hidayat";
        $person->save();

        self::assertEquals("Fauzan Nur Hidayat", $person->full_name);

        $person->full_name = "Fauzan Nurhidayat";
        $person->save();

        self::assertEquals("Fauzan", $person->first_name);
        self::assertEquals("Nurhidayat", $person->last_name);
    }

    public function testSerialization()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $products = Product::get();
        self::assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }

    public function testSerializationRelation()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);
        $products = Product::get();
        $products->load(['category', 'image']);
        self::assertCount(2, $products);
        // var_dump($products->all()[1]);
        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }


}
