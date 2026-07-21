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
            ->with('category:id,name_ar,name_en')
            ->where('is_active', true)
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->integer('category_id'))
            )
            ->latest()
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'category' => [
                    'id' => $product->category?->id,
                    'name_ar' => $product->category?->name_ar,
                    'name_en' => $product->category?->name_en,
                ],
                'name_ar' => $product->name_ar,
                'name_en' => $product->name_en,
                'calories' => $product->calories,
                'price' => (float) $product->price,
                'image' => $product->image_url,
                'is_flag' => (bool) $product->is_flag,
            ]);

        return response()->json([
            'data' => $products,
        ]);
    }

    public function menu(): JsonResponse
    {
        $menu = Category::query()
            ->with(['products' => fn ($query) => $query->where('is_active', true)->latest()])
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                'image' => $category->image_url,
                'products' => $category->products->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name_ar' => $product->name_ar,
                    'name_en' => $product->name_en,
                    'calories' => $product->calories,
                    'price' => (float) $product->price,
                    'image' => $product->image_url,
                    'is_flag' => (bool) $product->is_flag,
                ])->values(),
            ]);

        return response()->json([
            'data' => $menu,
        ]);
    }
}
