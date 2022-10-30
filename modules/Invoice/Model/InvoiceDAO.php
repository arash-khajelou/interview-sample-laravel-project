<?php

namespace Modules\Invoice\Model;

use Carbon\Carbon;
use Common\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Person\Model\PersonDAO;
use Modules\Product\Model\ProductDAO;

/**
 * @property int id
 * @property int person_id
 * @property int total_sum
 *
 * @property InvoiceItemDAO[] items
 * @property PersonDAO person
 * @property ProductDAO[] products
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class InvoiceDAO extends BaseModel
{
    use HasFactory;

    protected $table = "invoices";

    protected $fillable = [
        "person_id", "total_sum"
    ];

    protected $with = [
        "items"
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductDAO::class, "invoice_items", "invoice_id", "product_id");
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItemDAO::class, "invoice_id", "id");
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(PersonDAO::class, "person_id", "id");
    }

    protected static function newFactory(): InvoiceFactory
    {
        return InvoiceFactory::new();
    }
}
