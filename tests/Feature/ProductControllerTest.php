<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Model\ProductService;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->initDatabase();
    }

    public function test_create_method()
    {
        $product_name = "Product " . fake()->colorName();
        $response = $this->post("/api/v1/product", [
            "name" => $product_name,
            "is_active" => true,
            "sell_price" => fake()->randomNumber(6),
            "tax_percentage" => ([3, 6, 9])[rand(0, 2)],
            "discount_percentage" => ([3, 5, 10, 15])[rand(0, 3)],
            "inventory" => 100
        ])->decodeResponseJson();

        $content = json_decode($response->json, true);
        $this->assertArrayHasKey("data", $content);
        $this->assertArrayHasKey("name", $content["data"]);
        $this->assertEquals($product_name, $content["data"]["name"]);
    }

    public function test_view_method()
    {

        $product = ProductService::getById(10);
        $response = $this->get("/api/v1/product/10");
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("data", $content);
        $this->assertArrayHasKey("name", $content["data"]);
        $this->assertEquals($product->name, $content["data"]["name"]);
    }

    public function test_index_method()
    {
        $products = ProductService::getAll();
        $response = $this->get("/api/v1/product");
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("data", $content);
        $this->assertEquals($products->toJson(), json_encode($content["data"]));
    }

    public function test_update_method()
    {
        $product = ProductService::getById(10);
        $response = $this->put("/api/v1/product/10", array_merge($product->toArray(),
                [
                    "name" => "My Awesome Product"
                ])
        )->decodeResponseJson();

        $response = $this->get("/api/v1/product/10");
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("data", $content);
        $this->assertArrayHasKey("name", $content["data"]);
        $this->assertEquals("My Awesome Product", $content["data"]["name"]);
    }

    public function test_activate_method()
    {
        ProductService::deactivateProduct(10);
        $response = $this->patch("/api/v1/product/10/activate")->decodeResponseJson();
        $product = ProductService::getById(10);
        $this->assertTrue($product->is_active);
    }

    public function test_deactivate_method()
    {
        ProductService::activateProduct(10);
        $response = $this->patch("/api/v1/product/10/deactivate")->decodeResponseJson();
        $product = ProductService::getById(10);
        $this->assertFalse($product->is_active);
    }
}
