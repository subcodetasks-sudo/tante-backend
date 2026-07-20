<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'button_1_name',
        'button_2_name',
        'video',
    ];

    protected $appends = [
        'video_url',
    ];

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video) {
            return null;
        }

        return asset('storage/'.$this->video);
    }
}
