<?php

namespace Modules\Invoice\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Invoice\Exceptions\InvoiceItemNotFoundException;
use Modules\Invoice\Exceptions\InvoiceNotFoundException;
use Modules\Invoice\Exceptions\InvoicePersonNotActiveException;
use Modules\Invoice\Exceptions\InvoiceProductNotActiveException;
use Modules\Invoice\Exceptions\InvoiceProductNotSufficientException;
use Modules\Person\Model\PersonDAO;
use Modules\Product\Events\InvoiceProductEdited;
use Modules\Product\Model\ProductDAO;

class InvoiceService
{
    /**
     * @throws InvoiceNotFoundException
     */
    public static function getById(int $id): InvoiceDAO
    {
        $person = InvoiceDAO::find($id);
        if ($person == null)
            throw new InvoiceNotFoundException("The invoice with id $id not found.");
        return $person;
    }

    /**
     * @throws InvoiceItemNotFoundException
     */
    public static function getItemById(int $id): InvoiceItemDAO
    {
        $person = InvoiceItemDAO::find($id);
        if ($person == null)
            throw new InvoiceItemNotFoundException("The invoice item with id $id not found.");
        return $person;
    }

    /**
     * @return InvoiceDAO[]|Collection
     */
    public static function getAll(): array|Collection
    {
        return InvoiceDAO::all();
    }

    /**
     * @throws InvoicePersonNotActiveException
     * @throws InvoiceProductNotActiveException
     * @throws InvoiceProductNotSufficientException
     */
    public static function addInvoice(PersonDAO $person, array $items = []): InvoiceDAO|Model
    {
        if (!$person->is_active)
            throw new InvoicePersonNotActiveException("The person with id {$person->id} is not active");

        $invoice = $person->invoices()->create([
            "total_sum" => 0
        ]);

        foreach ($items as $item) {
            InvoiceService::addInvoiceItem($invoice, $item["product_id"], $item["quantity"], false);
        }

        InvoiceService::calculateInvoice($invoice, true);
        return $invoice;
    }

    /**
     * @throws InvoiceNotFoundException
     */
    public static function deleteInvoice(int $id): ?bool
    {
        $invoice = InvoiceService::getById($id);

        foreach ($invoice->items as $item) {
            InvoiceProductEdited::dispatch($item->product_id, $item->quantity);
        }

        return $invoice->delete();
    }

    /**
     * @throws InvoiceProductNotActiveException
     * @throws InvoiceProductNotSufficientException
     */
    public static function addInvoiceItem(InvoiceDAO|Model $invoice, ProductDAO $product, float $quantity, bool $calculate = true): InvoiceItemDAO
    {
        if (!$product->is_active)
            throw new InvoiceProductNotActiveException("The product with id {$product->id} is not active so could not be added to the invoice");

        if ($product->inventory < $quantity) {
            throw new InvoiceProductNotSufficientException("The product with id {$product->id} is not sufficient for this invoice");
        }

        $amount = $product->sell_price * $quantity;
        $discount_amount = (int)($amount * $product->discount_percentage / 100);
        $after_discount_amount = $amount - $discount_amount;
        $tax_amount = (int)($after_discount_amount * $product->tax_percentage / 100);
        $total_due = $after_discount_amount + $tax_amount;

        $invoice_item = $invoice->items()->create([
            "product_id" => $product->id,
            "quantity" => $quantity,
            "amount" => $amount,
            "discount_amount" => $discount_amount,
            "after_discount_amount" => $after_discount_amount,
            "tax_amount" => $tax_amount,
            "total_due" => $total_due
        ]);

        if ($calculate)
            InvoiceService::calculateInvoice($invoice);

        foreach ($invoice->items as $item) {
            InvoiceProductEdited::dispatch($item->product_id, -$item->quantity);
        }

        return $invoice_item;
    }

    /**
     * @throws InvoiceItemNotFoundException
     * @throws InvoiceNotFoundException
     */
    public static function deleteInvoiceItemByItemId(int $item_id, bool $calculate = true): bool
    {
        $invoice_item = InvoiceService::getItemById($item_id);
        $invoice_id = $invoice_item->invoice_id;
        $result = $invoice_item->delete();
        if ($result) {
            $invoice = InvoiceService::getById($invoice_id);
            if ($calculate)
                InvoiceService::calculateInvoice($invoice);
        }
        return $result;
    }

    /**
     * @throws InvoiceItemNotFoundException
     */
    public static function deleteInvoiceItemByProductId(InvoiceDAO $invoice, int $product_id, bool $calculate = true): ?bool
    {
        $invoice_item = $invoice->items()->where("product_id", $product_id)->first();
        if ($invoice_item == null)
            throw new InvoiceItemNotFoundException("The invoice item with (invoice_id: $invoice, product_id: $product_id) not found.");
        $result = $invoice_item->delete();
        if ($result) {
            if ($calculate)
                InvoiceService::calculateInvoice($invoice);
        }
        return $result;
    }

    private static function calculateInvoice(InvoiceDAO|Model $invoice): void
    {
        $invoice->total_sum = 0;
        foreach ($invoice->items as $item) {
            $invoice->total_sum += $item->total_due;
        }
        $invoice->save();
    }
}
