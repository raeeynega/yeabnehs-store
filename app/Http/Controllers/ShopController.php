<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'images']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest' => $query->orderByDesc('created_at'),
                'name' => $query->orderBy('name'),
                default => $query->orderBy('sort_order'),
            };
        } else {
            $query->orderBy('sort_order');
        }

        if ($request->filled('clearance')) {
            $query->where('is_clearance', true);
        }

        $products = $query->paginate(24);
        $categories = Category::where('is_active', true)->withCount('products')->get();

        return view('shop', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->limit(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }
}
