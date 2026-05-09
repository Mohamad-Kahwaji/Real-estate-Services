<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DynamicField;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('', compact('categories'));
     /*   $categories = Category::with('subcategories')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                'subcategories' => $category->subcategories->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name_ar' => $subcategory->name_ar,
                        'name_en' => $subcategory->name_en,
                    ];
                }),
            ];
        });
        return response()->json([
          'data' => $categories,
          'message' => 'Categories and subcategories retrieved successfully',
        ]);*/
    }


    public function store(Request $request){
        $val = $request->validate([
            'name_ar'=>'required',
            'name_en'=>'required',
            'fields' => ['nullable', 'array'],
            'fields.*.name' => ['nullable', 'string', 'max:255'],
            'fields.*.label' => ['nullable', 'string', 'max:255'],
            'fields.*.type' => ['nullable', 'in:text,number,date,select'],
            'fields.*.is_required' => ['nullable', 'boolean'],
                ]);
        Category::create([
            'name_ar'=>$request->name_ar,
            'name_en'=>$request->name_en
        ]);
        foreach ($request->fields ?? [] as $field) {
        if (empty($field['name']) || empty($field['label']) || empty($field['type'])) {
            continue;
        }

        DynamicField::create([
            'category_id' => $category->id,
            'subcategory_id' => null,
            'name' => $field['name'],
            'label' => $field['label'],
            'type' => $field['type'],
            'is_required' => $field['is_required'] ?? false,
        ]);
        return redirect()->route('indexsuperadmin');
    }
    }

    public function update(Request $request,$id){
            $val = $request->validate([
            'name_ar'=>'required',
            'name_en'=>'required'
        ]);
        $category = Category::findOrFail($id)->update([
            'name_ar'=>$request->name_ar,
            'name_en'=>$request->name_en
        ]);
        return redirect()->route('indexsuperadmin');

        }


        public function destroy($id){
            $rem = Category::findOrFail($id);
            $rem->delete();
            return redirect()->route('indexsuperadmin');
        }


}
