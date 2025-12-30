<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the specified product detail page.
     */
    public function show(Product $product)
    {
        // Eager load reviews with user
        $product->load(['reviews.user', 'seller']);
        
        // Related products from the same seller (exclude current)
        $relatedBySeller = Product::where('user_id', $product->user_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->orderByDesc('sold')
            ->take(6)
            ->get();

        // More products from the same shop (can overlap or additional)
        $moreFromShop = Product::where('user_id', $product->user_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // Related products from the same category but different users
        $relatedByCategory = Product::where('category', $product->category)
            ->where('user_id', '!=', $product->user_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->orderByDesc('sold')
            ->take(8)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedBySeller' => $relatedBySeller,
            'moreFromShop' => $moreFromShop,
            'relatedByCategory' => $relatedByCategory,
        ]);
    }
}
