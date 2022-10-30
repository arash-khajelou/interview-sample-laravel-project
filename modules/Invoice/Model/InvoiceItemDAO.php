<?php

namespace Modules\Invoice\Model;

use Carbon\Carbon;
use Common\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Model\ProductDAO;

/**
 * @property int id
 * @property int product_id
 * @property int invoice_id
 * @property float quantity
 * @property int amount
 * @property int discount_amount
 * @property int after_discount_amount
 * @property int tax_amount
 * @property int total_due
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property ProductDAO product
 * @property InvoiceDAO invoice
 */
class InvoiceItemDAO extends BaseModel
{
    protected $table = "invoice_items";

    protected $fillable = [
        "product_id",
        "invoice_id",
        "quantity",
        "amount",
        "discount_amount",
        "after_discount_amount",
        "tax_amount",
        "total_due"
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductDAO::class, "product_id", "id");
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceDAO::class, "invoice_id", "id");
    }
}
