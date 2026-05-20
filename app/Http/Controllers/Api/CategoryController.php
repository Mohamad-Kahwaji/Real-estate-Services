<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DynamicField;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Fetches all categories and passes them to the categories view.
    public function index(){
        $categories = Category::all();
        return view('', compact('categories'));
    }


    // Validates and creates a new category, then attaches any submitted dynamic fields to it.
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

    // Updates the Arabic and English names of the specified category.
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


        // Permanently deletes the specified category and redirects to the super-admin dashboard.
        public function destroy($id){
            $rem = Category::findOrFail($id);
            $rem->delete();
            return redirect()->route('indexsuperadmin');
        }


}
