<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'name',
        'account_holder',
        'account_number',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
