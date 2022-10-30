<?php

namespace Modules\Product\EventListeners;

use Modules\Product\Events\InvoiceProductEdited;
use Modules\Product\Exceptions\ProductNotFoundException;
use Modules\Product\Model\ProductService;

class UpdateProductInventory
{
    /**
     * Handle the event.
     *
     * @param InvoiceProductEdited $event
     * @return void
     * @throws ProductNotFoundException
     */
    public function handle(InvoiceProductEdited $event): void
    {
        $product = ProductService::getById($event->getProductId());
        ProductService::updateProductInventory($product, $product->inventory + $event->getQuantity());
    }
}
