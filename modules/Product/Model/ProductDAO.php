<?php

namespace Modules\Product\Model;

use Common\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Invoice\Model\InvoiceDAO;
use Modules\Invoice\Model\InvoiceItemDAO;

/**
 * @property int id
 * @property string name
 * @property bool is_active
 * @property int sell_price
 * @property int tax_percentage
 * @property int discount_percentage
 * @property float inventory
 *
 * @property InvoiceDAO[] invoices
 * @property InvoiceItemDAO[] invoiceItems
 */
class ProductDAO extends BaseModel
{
    use HasFactory;

    protected $table = "products";

    protected $casts = [
        "is_active" => "boolean",
        "inventory" => "float"
    ];

    protected $fillable = [
        "name", "is_active", "sell_price", "tax_percentage", "discount_percentage", "inventory"
    ];

    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(
            InvoiceItemDAO::class,
            InvoiceDAO::class,
            "product_id",
            "invoice_id",
            "id",
            "id");
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItemDAO::class, "product_id", "id");
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
