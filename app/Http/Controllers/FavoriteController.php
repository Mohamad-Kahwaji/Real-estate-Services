<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function add($service){
      $user = request()->user();
      $user->favorites()->syncWithoutDetaching([$service]);
      return back()->with('success', 'Service added to favorites successfully.');
    }
    public function remove($service)
{
    $user = request()->user();
    $user->favorites()->detach([$service]);
    return back()->with('success', 'Service removed from favorites');
}
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

public function index()
{
    $user      = request()->user();
    $favorites = $user->favorites()->with(['business', 'category','subcategory'])->get();
    return view('users.favorite', compact('favorites'));
}
}
