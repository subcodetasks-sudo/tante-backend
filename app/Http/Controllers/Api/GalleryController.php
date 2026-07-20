<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        $galleries = Gallery::query()
            ->orderBy('sort_order')
            ->latest('id')
            ->get()
            ->map(fn (Gallery $gallery) => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'type' => $gallery->type,
                'image' => $gallery->type === 'image' ? $gallery->image_url : null,
                'video_url' => $gallery->type === 'video' ? $gallery->video_url : null,
                'sort_order' => $gallery->sort_order,
            ]);

        return response()->json([
            'data' => $galleries,
        ]);
    }
}
