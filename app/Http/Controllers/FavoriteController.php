<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function add($service){
      $user = auth('users')->user();
      $user->favorites()->syncWithoutDetaching([$service]);
      return back()->with('success', 'Service added to favorites successfully.');
    }
    public function remove($service)
{
    $user = auth('users')->user();
    $user->favorites()->detach([$service]);
    return back()->with('success', 'Service removed from favorites');
}
public function toggle($id)
{
    $user = auth('users')->user();

    if ($user->favorites()->where('service_id', $id)->exists()) {
        $user->favorites()->detach($id); // حذف
    } else {
        $user->favorites()->attach($id); // إضافة
    }

    return back();
}
}
