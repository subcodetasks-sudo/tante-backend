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
            ->with(['category:id,name_ar,name_en', 'weights'])
            ->mostOrdered()
            ->latest()
            ->get()
            ->map(fn (Product $product) => $product->toApiArray());

        return response()->json([
            'data' => [
                'title' => $content?->title,
                'description' => $content?->description,
                'products' => $products,
            ],
        ]);
    }
}
