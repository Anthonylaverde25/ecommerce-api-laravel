<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Category Model - Infrastructure Layer (Eloquent ORM)
 * 
 * NOTA: Este archivo está en app/Models/ por convención de Laravel
 * y compatibilidad con herramientas de deployment.
 * 
 * Conceptualmente pertenece a Infrastructure Layer (persistencia).
 * NO es lo mismo que Domain\Category\Entities\Category.php
 * 
 * - Category.php (este)     = Eloquent Model para base de datos
 * - Domain\...\Category.php = Domain Entity con lógica de negocio
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación: una categoría puede tener muchas subcategorías
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relación: una categoría puede tener una categoría padre
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Scope: solo categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: solo categorías raíz (sin padre)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Items asociados a esta categoría (relación muchos-a-muchos)
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }
}
