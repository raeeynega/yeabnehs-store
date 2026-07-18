<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('images')
            ->limit(12)
            ->get();

        $newArrivals = Product::where('is_active', true)
            ->where('is_new', true)
            ->with('images')
            ->limit(8)
            ->get();

        $clearanceProducts = Product::where('is_active', true)
            ->where('is_clearance', true)
            ->with('images')
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->where('is_featured', true)
            ->withCount('products')
            ->get();

        $sliders = Slider::where('is_active', true)->orderBy('sort_order')->get();

        return view('home', compact('featuredProducts', 'newArrivals', 'clearanceProducts', 'categories', 'sliders'));
    }
}
