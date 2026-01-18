<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'active',
    ];

    public function taxes(): HasMany
    {
        return $this->hasMany(Tax::class);
    }
}
