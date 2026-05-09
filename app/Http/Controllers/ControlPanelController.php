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
    public function index()
    {
        $categories = Category::get();
        $subcategories = Subcategory::with('category')->latest()->get();
        $cities = City::latest()->get();
        $account = Business::with(['user', 'city'])
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
