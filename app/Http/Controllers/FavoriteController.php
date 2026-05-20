<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Adds a service to the authenticated user's favorites without removing existing ones.
    public function add($service){
        $user = request()->user();
        $user->favorites()->syncWithoutDetaching([$service]);
        return back()->with('success', 'Service added to favorites successfully.');
    }

    // Removes a service from the authenticated user's favorites.
    public function remove($service)
    {
        $user = request()->user();
        $user->favorites()->detach([$service]);
        return back()->with('success', 'Service removed from favorites');
    }

    // Toggles a service in or out of the authenticated user's favorites.
    public function toggle($id)
    {
        $user = request()->user();

        if ($user->favorites()->where('service_id', $id)->exists()) {
            $user->favorites()->detach($id);
        } else {
            $user->favorites()->attach($id, ['favorite' => 1]);
        }

        return back();
    }

    // Loads the authenticated user's favorited services with business, category, and subcategory data.
    public function index()
    {
        $user      = request()->user();
        $favorites = $user->favorites()->with(['business', 'category', 'subcategory'])->get();
        return view('users.favorite', compact('favorites'));
    }
}
