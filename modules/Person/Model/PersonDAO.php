<?php

namespace Modules\Person\Model;

use Carbon\Carbon;
use Common\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Invoice\Model\InvoiceDAO;

/**
 * @property int id
 * @property string demonstration_name
 * @property bool is_active
 * @property string first_name
 * @property string last_name
 * @property string social_id
 * @property Carbon birth_date
 * @property string mobile_number
 * @property string mobile_description
 * @property string email
 * @property string email_description
 */
class PersonDAO extends BaseModel
{
    use HasFactory;


    protected $table = "persons";

    protected $fillable = [
        "is_active",
        "first_name",
        "last_name",
        "social_id",
        "birth_date",
        "mobile_number",
        "mobile_description",
        "email",
        "email_description",
    ];

    protected $casts = [
        "is_active" => "boolean",
        "birth_data" => "timestamp"
    ];

    private function buildDemonstrationName(): void
    {
        $this->attributes["demonstration_name"] =
            ($this->attributes["first_name"] ?? "") . " " .
            ($this->attributes["last_name"] ?? "");
    }

    public function setFirstNameAttribute($value): void
    {
        $this->attributes["first_name"] = $value;
        $this->buildDemonstrationName();
    }

    public function setLastNameAttribute($value): void
    {
        $this->attributes["last_name"] = $value;
        $this->buildDemonstrationName();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceDAO::class, "peron_id", "id");
    }

    protected static function newFactory(): PersonFactory
    {
        return PersonFactory::new();
    }
}
