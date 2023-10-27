<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    
    public function testCreateVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->is_active = true;
        // $voucher->voucher_code = '123123123123';
        $voucher->save();
        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
        self::assertEquals(true, $voucher->is_active);
    }

    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->is_active = true;
        $voucher->save();

        $total = Voucher::active()->count();
        // var_dump($total);
        self::assertEquals(1, $total);
    } 

    
}
