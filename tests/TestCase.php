<?php

namespace Tests;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Person;
use App\Models\Product;
use App\Models\Review;
use App\Models\VirtualAccount;
use App\Models\Voucher;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from customers_likes_products");
        DB::delete("delete from products");
        DB::delete("delete from categories");
        DB::delete("delete from virtual_accounts");
        DB::delete("delete from wallets");
        DB::delete("delete from customers");
        DB::delete("delete from employees");
        DB::delete("delete from people");
        DB::delete("delete from tags");
        DB::delete("delete from taggables");
        DB::delete("delete from images");
        DB::delete("delete from reviews");
        DB::delete("delete from vouchers");
        DB::delete("delete from comments");
    }
}
