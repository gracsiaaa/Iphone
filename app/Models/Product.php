<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'type', 'color', 'capacity', 'price', 'stock', 'description', 'is_active', 'is_featured'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'stock' => 'integer', 'is_active' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $path = $this->images->firstWhere('is_primary', true)?->path ?? $this->images->first()?->path;
        return $path ? Storage::url($path) : asset('images/products/iphone-placeholder.svg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp'.number_format((float) $this->price, 0, ',', '.');
    }

    public function getSkuAttribute(): string
    {
        return strtoupper(str_replace(' ', '-', "{$this->type}-{$this->capacity}-{$this->color}"));
    }
}
