<?php

namespace App\Http\Controllers;

use App\Models\Ads;
//use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    public function index(){
      $ads = Ads::where('is_active', true)->get()
      ->map(function ($ad) {
        return [
          'id' => $ad->id,
          'title' => $ad->title,
          'description' => $ad->description,
          'image' => asset('storage/' . $ad->image),
          'is_active' => $ad->is_active,
        ];
      });
    return response()->json([
      'ads' => $ads,
      'status' => 'true',
    ]);
    }

    public function store(Request $request){
      $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'is_active' => 'boolean',
        ]);
        $saveimage = $request->file('image')->store('ads', 'public');
        $ads = Ads::create([
          'title' => $request->title,
          'description' => $request->description,
          'image' => $saveimage,
          'is_active' => $request->is_active ?? true,
        ]);
        return response()->json([
          'ads' => $ads,
          'status' => 'true',
          'message' => 'Ad created successfully',
        ]);
    }

    public function show($id){
      $ads = Ads::findOrFail($id);

      return response()->json([
        'status' => 'true',
        'data'=>[
          'id'=>$ads->id,
          'title'=>$ads->title,
          'description'=>$ads->description,
          'image'=>$ads->image,
          'is_active'=>$ads->is_active,
        ]
      ]);
    }

    public function update(Request $request,$id){
      $val = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'is_active' => 'boolean',
        ]);
        $ads = Ads::findOrFail($id);
        if($request->hasFile('image')){
          if($ads->image){
            Storage::disk('public')->delete($ads->image);
          }
          $val = $request->file('image')->store('ads', 'public');
        }
        $ads->update($val);
        return response()->json([
          'ads' => $ads,
          'status' => 'true',
          'message' => 'Ad updated successfully',
        ]);
    }

    public function destroy($id){
      $ads = Ads::findOrFail($id);
      if($ads->image){
        Storage::disk('public')->delete($ads->image);
      }
      $ads->delete();
      return response()->json([
        'status' => 'true',
        'message' => 'Ad deleted successfully',
      ]);
    }
}
