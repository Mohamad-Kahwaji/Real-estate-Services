<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DynamicField;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List all categories with their subcategory counts for the super-admin panel.
    public function index(){
        $categories = Category::withCount('subcategory')->get();
        return view('super_admin.categories', compact('categories'));
    }

    // Validate and create a new category along with any submitted dynamic fields.
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
        $category = Category::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);
        foreach ($request->fields ?? [] as $field) {
            if (empty($field['name']) || empty($field['label']) || empty($field['type'])) {
                continue;
            }
            DynamicField::create([
                'category_id'    => $category->id,
                'subcategory_id' => null,
                'name'           => $field['name'],
                'label'          => $field['label'],
                'type'           => $field['type'],
                'is_required'    => $field['is_required'] ?? false,
            ]);
        }
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    // Update a category's Arabic and English names.
    public function update(Request $request,$id){
        $val = $request->validate([
            'name_ar'=>'required',
            'name_en'=>'required'
        ]);
        $category = Category::findOrFail($id)->update([
            'name_ar'=>$request->name_ar,
            'name_en'=>$request->name_en
        ]);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // Permanently delete a category by ID.
    public function destroy($id){
        Category::findOrFail($id)->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }


}
