<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchContent;
use Illuminate\Http\JsonResponse;

class BranchContentController extends Controller
{
    public function index(): JsonResponse
    {
        $content = BranchContent::query()->latest()->first();

        if (! $content) {
            return response()->json([
                'message' => 'No branch content found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $content->id,
                'title' => $content->title,
                'description' => $content->description,
            ],
        ]);
    }
}
