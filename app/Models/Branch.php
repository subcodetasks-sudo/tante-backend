<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'open_from',
        'open_to',
    ];

    public function getOpenFromFormattedAttribute(): ?string
    {
        return $this->open_from ? substr((string) $this->open_from, 0, 5) : null;
    }

    public function getOpenToFormattedAttribute(): ?string
    {
        return $this->open_to ? substr((string) $this->open_to, 0, 5) : null;
    }
}
