<?php

namespace Modules\Product\Controller;

use Carbon\Carbon;
use Common\BaseController;
use Common\MessageFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Exceptions\ProductNotFoundException;
use Modules\Product\Model\ProductService;
use Modules\Product\Requests\StoreProductRequest;
use Modules\Product\Requests\UpdateProductRequest;

class ProductController extends BaseController
{
    public function index(): JsonResponse
    {
        $products = ProductService::getAll();
        return MessageFactory::jsonResponse([], 200, $products);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $product = ProductService::getById($id);
            return MessageFactory::jsonResponse([], 200, $product);
        } catch (ProductNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.product.query.not_found"], 404, []);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = ProductService::createProduct(
                $request->get("name"),
                $request->get("is_active"),
                $request->get("sell_price"),
                $request->get("tax_percentage"),
                $request->get("discount_percentage"),
                $request->get("inventory"),
            );
            return MessageFactory::jsonResponse(["api.product.create.success"], 200, $product);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.product.create.failed"], 400, []);
        }

    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            ProductService::updateProduct(
                $id,
                $request->get("name"),
                $request->get("is_active"),
                $request->get("sell_price"),
                $request->get("tax_percentage"),
                $request->get("discount_percentage"),
                $request->get("inventory"),
            );
            return MessageFactory::jsonResponse(["api.product.update.success" => ["id" => $id]], 200, []);
        } catch (ProductNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.product.update.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.product.update.failed" => ["id" => $id], $e->getMessage()], 400, []);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            ProductService::deleteProduct($id);
            return MessageFactory::jsonResponse(["api.product.delete.success" => ["id" => $id]], 200, []);
        } catch (ProductNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.product.delete.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.product.delete.failed" => ["id" => $id]], 400, []);
        }
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        try {
            ProductService::activateProduct($id);
            return MessageFactory::jsonResponse(["api.product.activation.success" => ["id" => $id]], 200, []);
        } catch (ProductNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.product.activation.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.product.activation.failed" => ["id" => $id]], 400, []);
        }
    }

    public function deactivate(Request $request, int $id): JsonResponse
    {
        try {
            ProductService::deactivateProduct($id);
            return MessageFactory::jsonResponse(["api.product.deactivation.success" => ["id" => $id]], 200, []);
        } catch (ProductNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.product.deactivation.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.product.deactivation.failed" => ["id" => $id]], 400, []);
        }
    }
}
