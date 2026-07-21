<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name_ar',
        'name_en',
        'calories',
        'price',
        'image',
        'is_active',
        'is_flag',
    ];

    protected static function booted(): void
    {
        static::saved(function (Product $product): void {
            if ($product->weights()->exists()) {
                $updates = [];

                if ($product->price !== null) {
                    $updates['price'] = null;
                }

                if ($product->calories !== null) {
                    $updates['calories'] = null;
                }

                if ($updates !== []) {
                    $product->updateQuietly($updates);
                }
            }
        });
    }

    protected $appends = [
        'image_url',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_flag' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function weights(): HasMany
    {
        return $this->hasMany(ProductWeight::class)->orderBy('sort_order');
    }

    public function scopeMostOrdered($query)
    {
        return $query->where('is_flag', true)->where('is_active', true);
    }

    public function hasWeights(): bool
    {
        if ($this->relationLoaded('weights')) {
            return $this->weights->isNotEmpty();
        }

        return $this->weights()->exists();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return asset('storage/'.$this->image);
    }

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'en' ? $this->name_en : $this->name_ar;
    }

    public function toApiArray(): array
    {
        $weights = $this->weights->map(fn (ProductWeight $weight) => [
            'id' => $weight->id,
            'weight' => $weight->weight,
            'calories' => $weight->calories,
            'price' => (float) $weight->price,
        ])->values();

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => [
                'id' => $this->category?->id,
                'name_ar' => $this->category?->name_ar,
                'name_en' => $this->category?->name_en,
            ],
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'calories' => $weights->isNotEmpty() ? null : $this->calories,
            'price' => $weights->isNotEmpty() ? null : ($this->price !== null ? (float) $this->price : null),
            'weights' => $weights,
            'image' => $this->image_url,
            'is_flag' => (bool) $this->is_flag,
        ];
    }

    public function toMenuApiArray(): array
    {
        $weights = $this->weights->map(fn (ProductWeight $weight) => [
            'id' => $weight->id,
            'weight' => $weight->weight,
            'calories' => $weight->calories,
            'price' => (float) $weight->price,
        ])->values();

        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'calories' => $weights->isNotEmpty() ? null : $this->calories,
            'price' => $weights->isNotEmpty() ? null : ($this->price !== null ? (float) $this->price : null),
            'weights' => $weights,
            'image' => $this->image_url,
            'is_flag' => (bool) $this->is_flag,
        ];
    }
}
