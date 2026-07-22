<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    public function index(): JsonResponse
    {
        $about = About::query()->latest()->first();

        if (! $about) {
            return response()->json([
                'message' => 'No about found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $about->id,
                'title' => $about->title,
                'description' => $about->description,
                'content' => $about->content,
                'image' => $about->image_url,
                'video' => $about->video_url,
            ],
        ]);
    }
}
