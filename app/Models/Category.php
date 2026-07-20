<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'sort_order',
    ];

    protected $appends = [
        'image_url',
        'name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
}
