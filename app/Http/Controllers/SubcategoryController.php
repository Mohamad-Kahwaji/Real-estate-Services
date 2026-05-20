<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DynamicField;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    // List all subcategories with their parent categories and dynamic fields for the super-admin panel.
    public function index(){
        $subcategories = Subcategory::with(['category', 'dynamicFields'])->get();
        $categories    = Category::all();
        return view('super_admin.subcategories', compact('subcategories', 'categories'));
    }

    // Validate and create a new subcategory with its dynamic fields.
    public function store(Request $request){
        $request->validate([
            'name_ar'               => 'required',
            'name_en'               => 'required',
            'category_id'           => 'required|exists:categories,id',
            'fields'                => 'nullable|array',
            'fields.*.name'         => 'required_with:fields|string|max:255',
            'fields.*.label_ar'     => 'required_with:fields|string|max:255',
            'fields.*.label_en'     => 'required_with:fields|string|max:255',
            'fields.*.type'         => 'required_with:fields|in:text,number,date,select',
            'fields.*.is_required'  => 'nullable|boolean',
            'fields.*.options'      => 'nullable|string',
        ]);

        $subcategory = Subcategory::create([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'category_id' => $request->category_id,
        ]);

        foreach ($request->fields ?? [] as $field) {
            if (empty($field['name']) || empty($field['label_ar']) || empty($field['type'])) {
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
                'label'          => $field['label_ar'],
                'label_ar'       => $field['label_ar'],
                'label_en'       => $field['label_en'] ?? null,
                'type'           => $field['type'],
                'is_required'    => isset($field['is_required']) ? true : false,
                'options'        => $options,
            ]);
        }

        return redirect()->route('subcategories.index')->with('success', 'Subcategory added successfully.');
    }

    // Update a subcategory's names and category, replacing all its dynamic fields.
    public function update(Request $request, $id){
        $request->validate([
            'name_ar'               => 'required',
            'name_en'               => 'required',
            'category_id'           => 'required|exists:categories,id',
            'fields'                => 'nullable|array',
            'fields.*.name'         => 'required_with:fields|string|max:255',
            'fields.*.label_ar'     => 'required_with:fields|string|max:255',
            'fields.*.label_en'     => 'required_with:fields|string|max:255',
            'fields.*.type'         => 'required_with:fields|in:text,number,date,select',
            'fields.*.is_required'  => 'nullable|boolean',
            'fields.*.options'      => 'nullable|string',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'category_id' => $request->category_id,
        ]);

        DynamicField::where('subcategory_id', $id)->delete();

        foreach ($request->fields ?? [] as $field) {
            if (empty($field['name']) || empty($field['label_ar']) || empty($field['type'])) {
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
                'label'          => $field['label_ar'],
                'label_ar'       => $field['label_ar'],
                'label_en'       => $field['label_en'] ?? null,
                'type'           => $field['type'],
                'is_required'    => isset($field['is_required']) ? true : false,
                'options'        => $options,
            ]);
        }

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    // Return the subcategory edit view stub (not yet wired to a specific record).
    public function edit(){
        return view('subcategory');
    }

    // Permanently delete a subcategory by ID.
    public function destroy($id){
        Subcategory::findOrFail($id)->delete();
        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted.');
    }
}
