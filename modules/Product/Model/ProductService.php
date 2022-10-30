<?php

namespace Modules\Product\Model;

use Illuminate\Database\Eloquent\Collection;
use Modules\Product\Exceptions\ProductNotFoundException;

class ProductService
{
    /**
     * @throws ProductNotFoundException
     */
    public static function getById(int $id): ProductDAO
    {
        $product = ProductDAO::find($id);
        if ($product == null)
            throw new ProductNotFoundException("The product with id $id not found.");
        return $product;
    }

    public static function createProduct(string $name,
                                         bool   $is_active,
                                         int    $sell_price,
                                         int    $tax_percentage,
                                         int    $discount_percentage,
                                         float  $inventory): ProductDAO
    {
        return ProductDAO::create([
            "name" => $name,
            "is_active" => $is_active,
            "sell_price" => $sell_price,
            "tax_percentage" => $tax_percentage,
            "discount_percentage" => $discount_percentage,
            "inventory" => $inventory
        ]);
    }

    public static function updateProduct(int    $id,
                                         string $name,
                                         bool   $is_active,
                                         int    $sell_price,
                                         int    $tax_percentage,
                                         int    $discount_percentage,
                                         float  $inventory): bool
    {
        $product = ProductService::getById($id);
        return $product->update(
            [
                "name" => $name,
                "is_active" => $is_active,
                "sell_price" => $sell_price,
                "tax_percentage" => $tax_percentage,
                "discount_percentage" => $discount_percentage,
                "inventory" => $inventory
            ]
        );
    }

    /**
     * @throws ProductNotFoundException
     */
    public static function deleteProduct(int $id): ?bool
    {
        $product = ProductService::getById($id);
        return $product->delete();
    }

    /**
     * @return Collection|ProductDAO[]
     */
    public static function getAll(): Collection|array
    {
        return ProductDAO::all();
    }


    /**
     * @throws ProductNotFoundException
     */
    public static function activateProduct(int $id): void
    {
        $product = ProductService::getById($id);
        if ($product->is_active)
            return;
        $product->update([
            "is_active" => true
        ]);
    }

    /**
     * @throws ProductNotFoundException
     */
    public static function deactivateProduct(int $id): void
    {
        $product = ProductService::getById($id);
        if (!$product->is_active)
            return;
        $product->update([
            "is_active" => false
        ]);
    }

    public static function updateProductInventory(ProductDAO $product, float $inventory): bool
    {
        return $product->update([
            "inventory" => $inventory
        ]);
    }
}
