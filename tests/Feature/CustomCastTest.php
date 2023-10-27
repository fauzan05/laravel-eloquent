<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomCastTest extends TestCase
{
    public function testCustomCast()
    {
        $person = new Person;
        $person->first_name = "Fauzan";
        $person->last_name = "Nurhidayat";
        $person->address = new Address("Jl.Tembana Km.12", "Kebumen", "Indonesia", "54361");
        $person->save();
        echo $person->id;
        $person = Person::find($person->id);
        self::assertNotNull($person->address);
        self::assertInstanceOf(Address::class, $person->address);
        self::assertEquals("Jl.Tembana Km.12", $person->address->street);
        self::assertEquals("Kebumen", $person->address->city);
        self::assertEquals("Indonesia", $person->address->country);
        self::assertEquals("54361", $person->address->postal_code);
    }
    
}
