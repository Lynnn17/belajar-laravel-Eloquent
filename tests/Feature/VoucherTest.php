<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    // id dan voucher code menggunakan uuid
    public function testCreateVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->voucher_code = "23415678";
        $voucher->save();

        self::assertNotNull($voucher->id);
    }

    public function testCreateVoucherCode()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }

    //untuk soft bisa menggunakan delete()
    // jika benar" ingin menghapus data bisa pakek forceDelete()

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::where('name', '=','Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::where('name','=','Sample Voucher')->first();
        self::assertNull($voucher);

        //jika ingin menggambil data termasuk yang sodt delete
        //maka bisa menggunakan withTrashed() saat membuat query

        $voucher = Voucher::withTrashed('name','=','Sample Voucher')->first();
        self::assertNotNull($voucher);

    }

    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample VOucher";
        $voucher->is_active = true;

        $voucher->save();

        $total = Voucher::active()->count();
        self::assertEquals(1, $total);

        $total = Voucher::nonActive()->count();
        self::assertEquals(0, $total);


    }




}
