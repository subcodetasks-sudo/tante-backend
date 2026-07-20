<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\Http\JsonResponse;

class HeroController extends Controller
{
    public function index(): JsonResponse
    {
        $hero = Hero::query()->latest()->first();

        if (! $hero) {
            return response()->json([
                'message' => 'No hero found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $hero->id,
                'title' => $hero->title,
                'description' => $hero->description,
                'content' => $hero->content,
                'button_1_name' => $hero->button_1_name,
                'button_2_name' => $hero->button_2_name,
                'video' => $hero->video_url,
            ],
        ]);
    }
}
