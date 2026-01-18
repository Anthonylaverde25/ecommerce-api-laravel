<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'cost_price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Categorías asociadas al item (relación muchos-a-muchos)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Almacenes asociados al item (relación muchos-a-muchos)
     */

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'item_warehouses')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    /**
     * Accessor inteligente para stock
     * 
     * Prioriza usar el valor cacheado de withSum('warehouses as stock', 'item_warehouses.quantity')
     * Si no está disponible, calcula en tiempo real
     */
    protected function stock(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Si ya fue cargado con withSum, úsalo (más eficiente)
                if (isset($this->attributes['stock'])) {
                    return (int) $this->attributes['stock'];
                }

                // Fallback: calcular en tiempo real desde la relación
                return $this->warehouses()->sum('item_warehouses.quantity') ?? 0;
            }
        );
    }

    /**
     * Scope para cargar el stock de forma optimizada
     */
    public function scopeWithStock($query)
    {
        return $query->withSum('warehouses as stock', 'item_warehouses.quantity');
    }



    /**
     * Scope para obtener el rango de precios dinamicos
     */

    public function scopePriceRange($query): array
    {
        $stats = $query->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        return [
            'min' => $stats->min_price ? (float) $stats->min_price : 0,
            'max' => $stats->max_price ? (float) $stats->max_price : 0

        ];
    }


    /**
     * Scope para obtener el precio mas taxes
     */

    protected function priceWithTaxes(): Attribute
    {
        return Attribute::make(
            get: function () {
                $basePrice = $this->price;
                $totalTaxPercentage = $this->taxes->sum('porcentaje');
                return round($basePrice * (1 + $totalTaxPercentage / 100), 2);
            }
        );
    }

    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $basePrice = $this->price;
                $totalTaxPercentage = $this->taxes->sum('porcentaje');

                return round($basePrice * ($totalTaxPercentage / 100), 2);
            }
        );
    }





    /**
     * Relaciones
     */
    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class);
    }


}
