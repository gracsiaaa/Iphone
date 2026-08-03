<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'color',
        'capacity',
        'price',
        'stock',
        'description',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->active()
            ->orderBy('ram')
            ->orderBy('storage');
    }

    public function allVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->orderBy('ram')
            ->orderBy('storage');
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
        $path = $this->images->firstWhere('is_primary', true)?->path
            ?? $this->images->first()?->path;

        return $path
            ? asset('storage/'.$path)
            : asset('images/products/iphone-placeholder.svg');
    }

    public function getMinimumPriceAttribute(): float
    {
        $minimum = $this->relationLoaded('variants')
            ? $this->variants->min('price')
            : $this->variants()->min('price');

        return (float) ($minimum ?? $this->price ?? 0);
    }

    public function getMaximumPriceAttribute(): float
    {
        $maximum = $this->relationLoaded('variants')
            ? $this->variants->max('price')
            : $this->variants()->max('price');

        return (float) ($maximum ?? $this->price ?? 0);
    }

    public function getTotalStockAttribute(): int
    {
        $total = $this->relationLoaded('variants')
            ? $this->variants->sum('stock')
            : $this->variants()->sum('stock');

        return (int) $total;
    }

    public function getFormattedPriceAttribute(): string
    {
        $minimum = $this->minimum_price;
        $maximum = $this->maximum_price;

        if ($minimum !== $maximum) {
            return 'Rp'.number_format($minimum, 0, ',', '.').' - Rp'.number_format($maximum, 0, ',', '.');
        }

        return 'Rp'.number_format($minimum, 0, ',', '.');
    }

    public function getSkuAttribute(): string
    {
        return strtoupper(str_replace(' ', '-', "{$this->type}-{$this->color}"));
    }
}
