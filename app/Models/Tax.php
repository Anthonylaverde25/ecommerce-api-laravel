<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tax extends Model
{
    protected $fillable = [
        'tax_type_id',
        'name',
        'tax_code',
        'porcentaje',
        'active',
        'description',
    ];


    public function taxType(): BelongsTo
    {
        return $this->belongsTo(TaxType::class);
    }

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }



}
