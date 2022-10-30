<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Person\Model\PersonService;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->initDatabase();
    }

    public function test_create_method()
    {
        $response = $this->post("/api/v1/person", [
            "is_active" => true,
            "first_name" => "Arash",
            "last_name" => "Khajelou",
            "social_id" => "0016789148",
            "birth_date" => "1994-04-07",
            "mobile_number" => "09129791146",
            "mobile_description" => "This is work mobile",
            "email" => "a.khajelou@gmail.com",
            "email_description" => "This is my work email"
        ])->decodeResponseJson();

        $content = json_decode($response->json, true);
        $this->assertArrayHasKey("data", $content);
        $this->assertArrayHasKey("first_name", $content["data"]);
        $this->assertEquals("Arash", $content["data"]["first_name"]);
    }

    public function test_view_method()
    {

        $person = PersonService::getById(10);
        $response = $this->get("/api/v1/person/10");
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("data", $content);
        $this->assertArrayHasKey("first_name", $content["data"]);
        $this->assertEquals($person->first_name, $content["data"]["first_name"]);
    }

    public function test_index_method()
    {
        $persons = PersonService::getAll();
        $response = $this->get("/api/v1/person");
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("data", $content);
        $this->assertEquals($persons->toJson(), json_encode($content["data"]));
    }

    public function test_update_method()
    {
        $person = PersonService::getById(10);
        $response = $this->put("/api/v1/person/10", array_merge($person->toArray(),
                [
                    "first_name" => "Arash",
                    "last_name" => "Khajelou"
                ])
        )->decodeResponseJson();

        $response = $this->get("/api/v1/person/10");
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("data", $content);
        $this->assertArrayHasKey("first_name", $content["data"]);
        $this->assertEquals("Arash", $content["data"]["first_name"]);
    }

    public function test_activate_method()
    {
        PersonService::deactivatePerson(10);
        $response = $this->patch("/api/v1/person/10/activate")->decodeResponseJson();
        $person = PersonService::getById(10);
        $this->assertTrue($person->is_active);
    }

    public function test_deactivate_method()
    {
        PersonService::activatePerson(10);
        $response = $this->patch("/api/v1/person/10/deactivate")->decodeResponseJson();
        $person = PersonService::getById(10);
        $this->assertFalse($person->is_active);
    }
}
