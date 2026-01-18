<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Family extends Model
{
    protected $table = 'family';
    protected $fillable = [
        'name',
        'code',
        'description',
        'active'
    ];


    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class);
    }


}