<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    public function testFactory()
    {
        $employee1 = Employee::factory()->programmer()->create([
            'id' => '1',
            'name' => 'Fauzan'
        ]);
        self::assertNotNull($employee1);
        $employee2 = Employee::factory()->seniorProgrammer()->create([
            'id' => '2',
            'name' => 'Susi'
        ]);
        self::assertNotNull($employee2);
    }
}
