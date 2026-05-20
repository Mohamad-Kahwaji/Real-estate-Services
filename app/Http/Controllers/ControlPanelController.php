<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class ControlPanelController extends Controller
{
    // Loads the control panel overview with categories, subcategories, cities, and pending business accounts.
    public function index()
    {
        $categories    = Category::get();
        $subcategories = Subcategory::with('category')->latest()->get();
        $cities        = City::latest()->get();
        $accounts      = Business::with(['user', 'city'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('', compact(
            'categories',
            'subcategories',
            'cities',
            'accounts'
        ));
    }
}
