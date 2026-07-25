<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;

class HomeController extends Controller
{
    // Public landing page: platform stats, featured services, and category browse list.
    public function index()
    {
        $stats = [
            'services'   => Service::where('status', 'approved')->count(),
            'businesses' => Business::where('status', 'approved')->count(),
            'cities'     => City::count(),
            'categories' => Category::count(),
        ];

        $featuredServices = Service::with(['business.city', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('subcategories')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        return view('home', compact('stats', 'featuredServices', 'categories'));
    }
}
