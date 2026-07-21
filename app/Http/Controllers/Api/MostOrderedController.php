<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MostOrderedContent;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class MostOrderedController extends Controller
{
    public function index(): JsonResponse
    {
        $content = MostOrderedContent::query()->latest()->first();

        $products = Product::query()
            ->with('category:id,name_ar,name_en')
            ->mostOrdered()
            ->latest()
            ->get()
            ->map(fn (Product $product) => $this->formatProduct($product));

        return response()->json([
            'data' => [
                'title' => $content?->title,
                'description' => $content?->description,
                'products' => $products,
            ],
        ]);
    }

    private function formatProduct(Product $product): array
    {
        return [
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
            'is_flag' => true,
        ];
    }
}
