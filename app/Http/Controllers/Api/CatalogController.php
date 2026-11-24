<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    public function products(): JsonResponse
    {
        $products = Product::query()
            ->where('status', 'active')
            ->latest()
            ->get()
            ->map(fn (Product $product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category,
                'price' => (float) $product->price,
                'image_url' => $product->image
                    ? Storage::disk('public')->url($product->image)
                    : null,
            ]);

        return response()->json(['data' => $products]);
    }

    public function categories(): JsonResponse
    {
        $categories = Product::query()
            ->where('status', 'active')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->map(fn (Product $product): array => [
                'id' => $product->category,
                'name' => $product->category,
            ]);

        return response()->json(['data' => $categories]);
    }
}
