<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DynamicField;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(){
        $subcategories = Subcategory::with(['category', 'dynamicFields'])->get();
        $categories    = Category::all();
        return view('super_admin.subcategories', compact('subcategories', 'categories'));
    }

    public function store(Request $request){
        $request->validate([
            'name_ar'          => 'required',
            'name_en'          => 'required',
            'category_id'      => 'required|exists:categories,id',
            'fields'           => 'nullable|array',
            'fields.*.name'    => 'required_with:fields|string|max:255',
            'fields.*.label'   => 'required_with:fields|string|max:255',
            'fields.*.type'    => 'required_with:fields|in:text,number,date,select',
            'fields.*.is_required' => 'nullable|boolean',
            'fields.*.options' => 'nullable|string',
        ]);

        $subcategory = Subcategory::create([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'category_id' => $request->category_id,
        ]);

        foreach ($request->fields ?? [] as $field) {
            if (empty($field['name']) || empty($field['label']) || empty($field['type'])) {
                continue;
            }
            $options = null;
            if ($field['type'] === 'select' && !empty($field['options'])) {
                $options = array_filter(array_map('trim', explode(',', $field['options'])));
            }
            DynamicField::create([
                'category_id'    => $request->category_id,
                'subcategory_id' => $subcategory->id,
                'name'           => $field['name'],
                'label'          => $field['label'],
                'type'           => $field['type'],
                'is_required'    => isset($field['is_required']) ? true : false,
                'options'        => $options,
            ]);
        }

        return redirect()->route('subcategories.index')->with('success', 'Subcategory added successfully.');
    }

    public function update(Request $request, $id){
        $request->validate([
            'name_ar'          => 'required',
            'name_en'          => 'required',
            'category_id'      => 'required|exists:categories,id',
            'fields'           => 'nullable|array',
            'fields.*.name'    => 'required_with:fields|string|max:255',
            'fields.*.label'   => 'required_with:fields|string|max:255',
            'fields.*.type'    => 'required_with:fields|in:text,number,date,select',
            'fields.*.is_required' => 'nullable|boolean',
            'fields.*.options' => 'nullable|string',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'category_id' => $request->category_id,
        ]);

        // Replace all dynamic fields with the new set
        DynamicField::where('subcategory_id', $id)->delete();

        foreach ($request->fields ?? [] as $field) {
            if (empty($field['name']) || empty($field['label']) || empty($field['type'])) {
                continue;
            }
            $options = null;
            if ($field['type'] === 'select' && !empty($field['options'])) {
                $options = array_filter(array_map('trim', explode(',', $field['options'])));
            }
            DynamicField::create([
                'category_id'    => $request->category_id,
                'subcategory_id' => $id,
                'name'           => $field['name'],
                'label'          => $field['label'],
                'type'           => $field['type'],
                'is_required'    => isset($field['is_required']) ? true : false,
                'options'        => $options,
            ]);
        }

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function edit(){
        return view('subcategory');
    }

    public function destroy($id){
        Subcategory::findOrFail($id)->delete();
        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted.');
    }
}
