<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with(['category:id,name_ar,name_en', 'weights'])
            ->where('is_active', true)
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->integer('category_id'))
            )
            ->latest()
            ->get()
            ->map(fn (Product $product) => $product->toApiArray());

        return response()->json([
            'data' => $products,
        ]);
    }

    public function menu(): JsonResponse
    {
        $menu = Category::query()
            ->with(['products' => fn ($query) => $query->where('is_active', true)->with('weights')->latest()])
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                'image' => $category->image_url,
                'products' => $category->products->map(fn (Product $product) => $product->toMenuApiArray())->values(),
            ]);

        return response()->json([
            'data' => $menu,
        ]);
    }
}
