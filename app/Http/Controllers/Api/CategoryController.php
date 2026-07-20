<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->latest('id')
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                'image' => $category->image_url,
                'products_count' => $category->products_count,
                'sort_order' => $category->sort_order,
            ]);

        return response()->json([
            'data' => $categories,
        ]);
    }
}
