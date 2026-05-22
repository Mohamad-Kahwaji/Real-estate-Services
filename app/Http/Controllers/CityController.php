<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    // List all cities for the super-admin panel.
    public function index(){
        $cities = City::withCount('services')
            ->with('services.category', 'services.business')
            ->get();
        return view('super_admin.cities', compact('cities'));
    }

    // Validate and create a new city with Arabic and English names.
    public function store(Request $request){
        $val = $request->validate([
            'name_ar'=>'required',
            'name_en'=>'required',
        ]);
        City::create([
            'name_ar'=>$request->name_ar,
            'name_en'=>$request->name_en,
        ]);
        return redirect()->route('cities.index')->with('success', 'Done.');
    }

    // Update a city's Arabic and English names.
    public function update(Request $request, int $id){
        $val = $request->validate([
            'name_ar'=>'required',
            'name_en'=>'required',
        ]);
        $val = City::findOrFail($id)->update([
            'name_ar'=>$request->name_ar,
            'name_en'=>$request->name_en,
        ]);
        return redirect()->route('cities.index')->with('success', 'Done.');
    }

    // Permanently delete a city by ID.
    public function destroy(int $id){
        City::findOrFail($id)->delete();
        return redirect()->route('cities.index')->with('success', 'Done.');
    }
}
