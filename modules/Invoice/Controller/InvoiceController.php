<?php

namespace Modules\Invoice\Controller;

use Common\BaseController;
use Common\MessageFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Invoice\Exceptions\InvoiceNotFoundException;
use Modules\Invoice\Exceptions\InvoicePersonNotActiveException;
use Modules\Invoice\Exceptions\InvoiceProductNotActiveException;
use Modules\Invoice\Exceptions\InvoiceProductNotSufficientException;
use Modules\Invoice\Model\InvoiceService;
use Modules\Invoice\Requests\DestroyInvoiceItemRequest;
use Modules\Invoice\Requests\StoreInvoiceItemRequest;
use Modules\Invoice\Requests\StoreInvoiceRequest;
use Modules\Person\Exceptions\PersonNotFoundException;
use Modules\Person\Model\PersonService;
use Modules\Product\Exceptions\ProductNotFoundException;
use Modules\Product\Model\ProductService;

class InvoiceController extends BaseController
{
    public function index(): JsonResponse
    {
        $invoices = InvoiceService::getAll();
        return MessageFactory::jsonResponse([], 200, $invoices);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $invoice = InvoiceService::getById($id);
            return MessageFactory::jsonResponse([], 200, $invoice);
        } catch (InvoiceNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.invoice.query.not_found"], 404, []);
        }
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        try {
            $person = PersonService::getById($request->get("person_id"));
            $invoice = InvoiceService::addInvoice($person, $request->get("items"));

            return MessageFactory::jsonResponse(["api.invoice.create.success"], 200, $invoice);
        } catch (PersonNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.invoice.create.person_not_found" => ["person_id" => $request->get("person_id")]], 400, []);
        } catch (InvoicePersonNotActiveException $e) {
            return MessageFactory::jsonResponse(["api.invoice.create.person_not_active" => ["person_id" => $request->get("person_id")]], 400, []);
        } catch (InvoiceProductNotActiveException $e) {
            return MessageFactory::jsonResponse(["api.invoice.create.product_not_active" => ["product_id" => $request->get("product_id")]], 400, []);
        } catch (InvoiceProductNotSufficientException $e) {
            return MessageFactory::jsonResponse(["api.invoice.create.product_not_sufficient" => ["product_id" => $request->get("product_id")]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.invoice.create.failed"], 500, []);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            InvoiceService::deleteInvoice($id);
            return MessageFactory::jsonResponse(["api.invoice.delete.success" => ["id" => $id]], 200, []);
        } catch (InvoiceNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.invoice.delete.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.invoice.delete.failed" => ["id" => $id]], 400, []);
        }
    }

    public function attach(StoreInvoiceItemRequest $request, int $id): JsonResponse
    {
        try {
            $invoice = InvoiceService::getById($id);
            $product = ProductService::getById($request->get("product_id"));
            $invoice_item = InvoiceService::addInvoiceItem($invoice, $product, $request->get("quantity"));
            return MessageFactory::jsonResponse(["api.invoice.attach.success"], 200, $invoice_item);
        } catch (InvoiceNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.invoice.attach.invoice_not_found"], 400, []);
        } catch (InvoiceProductNotActiveException $e) {
            return MessageFactory::jsonResponse(["api.invoice.attach.product_not_active"], 400, []);
        } catch (InvoiceProductNotSufficientException $e) {
            return MessageFactory::jsonResponse(["api.invoice.attach.product_not_sufficient"], 400, []);
        } catch (ProductNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.invoice.attach.product_not_found"], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.invoice.attach.product_not_found", $e->getMessage()], 400, []);
        }
    }

    public function detach(DestroyInvoiceItemRequest $request, int $id): JsonResponse
    {
        try {
            $invoice = InvoiceService::getById($id);
            $invoice_item = InvoiceService::deleteInvoiceItemByProductId($invoice, $request->get("product_id"));
            return MessageFactory::jsonResponse(["api.invoice.detach.success"], 200, $invoice_item);
        } catch (InvoiceNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.invoice.detach.invoice_not_found"], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.invoice.detach.product_not_found", $e->getMessage()], 400, []);
        }
    }
}
