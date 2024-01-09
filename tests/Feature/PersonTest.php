<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class PersonTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testPerson()
    {
       $person = new Person();
       $person->first_name = "Lyn";
       $person->last_name = "1702";
       $person->save();

       self::assertEquals("LYN 1702", $person->full_name);

       $person->full_name = "Bapak Kau";
       $person->save();

        assertEquals("BAPAK", $person->first_name);
        assertEquals("Kau", $person->last_name);
    }

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Lyn";
        $person->last_name = "1702";
        $person->save();

        assertNotNull($person->created_at);
        assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }


    //membuat tipe data baru
    public function testCustomCasts()
    {
        $person = new Person();
        $person->first_name = "Lyn";
        $person->last_name = "1702";
        $person->address = new Address("Jalan Belum Jadi", "Jakarta", "Indonesia", "11111");
        $person->save();

        assertNotNull($person->created_at);
        assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
        assertEquals("Jalan Belum Jadi", $person->address->street);
        assertEquals("Jakarta", $person->address->city);
        assertEquals("Indonesia", $person->address->country);
        assertEquals("11111", $person->address->postal_code);
    }


}
