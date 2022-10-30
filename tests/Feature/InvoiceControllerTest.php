<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Invoice\Model\InvoiceService;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->initDatabase();
    }

    public function test_index_method()
    {
        $response = $this->get("/api/v1/invoice")->decodeResponseJson();
        $content = json_decode($response->json, true);
        $this->assertArrayHasKey("data", $content);
        $this->assertEquals(json_encode($content["data"]), InvoiceService::getAll()->toJson());
    }

    public function test_create_method()
    {
        $response = $this->post("/api/v1/invoice", [
            "person_id" => 10,
            "items" => [
                [
                    "product_id" => 10,
                    "quantity" => 2.0
                ],
                [
                    "product_id" => 12,
                    "quantity" => 2.0
                ]
            ]
        ]);

        $this->assertTrue(true);
    }
}
