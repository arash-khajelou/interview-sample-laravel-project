<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Modules\Person\Model\PersonDAO;
use Modules\Person\Model\PersonService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonModelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->initDatabase();
    }

    public function test_create_user()
    {
        $person = PersonService::createPerson(true, "Arash", "Khajelou", "0016789148",
            Carbon::createFromFormat("Y-m-d", "1994-04-07"), "09129791146", "This is main mobile",
            "a.khajelou@gmail.com", "This is work email");

        $this->assertIsNumeric($person->id);
        $this->assertEquals("Arash", $person->first_name);
        $this->assertEquals("Khajelou", $person->last_name);
        $this->assertEquals("0016789148", $person->social_id);
        $this->assertEquals(Carbon::createFromFormat("Y-m-d", "1994-04-07"), $person->birth_date);
        $this->assertEquals("09129791146", $person->mobile_number);
        $this->assertEquals("This is main mobile", $person->mobile_description);
        $this->assertEquals("a.khajelou@gmail.com", $person->email);
        $this->assertEquals("This is work email", $person->email_description);
    }

    public function test_building_demonstration_name()
    {
        $person = PersonDAO::find(10);
        $this->assertEquals($person->demonstration_name, $person->first_name . " " . $person->last_name);
    }
}
