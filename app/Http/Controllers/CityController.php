<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(){
        $cities = City::get();
        return view('super_admin.cities', compact('cities'));
    }

      public function store(Request $request){
          $val = $request->validate([
              //'user_id'=>'required',
              'name_ar'=>'required',
              'name_en'=>'required',
              ]);
          City::create([
              'name_ar'=>$request->name_ar,
              'name_en'=>$request->name_en,
          ]);
          return redirect()->route('cities.index')->with('success', 'Done.');
      }


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


    public function destroy(int $id){
        City::findOrFail($id)->delete();
        return redirect()->route('cities.index')->with('success', 'Done.');
    }
}
