<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'restaurant_name',
        'logo',
        'description',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'tiktok',
        'whatsapp',
    ];

    protected $appends = [
        'logo_url',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return asset('storage/'.$this->logo);
    }
}
