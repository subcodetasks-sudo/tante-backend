<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'video',
    ];

    protected $appends = [
        'image_url',
        'video_url',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return asset('storage/'.$this->image);
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video) {
            return null;
        }

        return asset('storage/'.$this->video);
    }
}
