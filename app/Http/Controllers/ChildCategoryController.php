<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Http\Request;

class ChildCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        // Get subcategories only if a category is selected
        $subCategories = [];
        if ($request->has('category_id') && $request->category_id) {
            $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        }

        $childCategories = ChildCategory::with(['category', 'subCategory'])->get();

        return view('dashboard.admin.childCategory', compact('categories', 'subCategories', 'childCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'childCategory' => 'required|unique:child_categories,name',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        ChildCategory::create([
            'name' => $request->childCategory,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
        ]);

        return redirect()->route('admin.childCategory.index')->with('success', 'Child Category created successfully.');
    }

    public function edit(ChildCategory $childCategory)
    {
        $categories = Category::all();
        $subCategories = SubCategory::where('category_id', $childCategory->category_id)->get();
        $childCategories = ChildCategory::with(['category', 'subCategory'])->get();

        return view('dashboard.admin.childCategory', compact('childCategory', 'categories', 'subCategories', 'childCategories'));
    }


    public function update(Request $request, ChildCategory $childCategory)
    {
        $request->validate([
            'childCategory' => 'required|unique:child_categories,name,' . $childCategory->id,
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        $childCategory->update([
            'name' => $request->childCategory,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
        ]);

        return redirect()->route('admin.childCategory.index')->with('success', 'Child Category updated successfully.');
    }

    public function destroy(ChildCategory $childCategory)
    {
        $childCategory->delete();
        return redirect()->route('admin.childCategory.index')->with('success', 'Child Category deleted successfully.');
    }

    public function getSubCategories($category_id)
    {
        $subCategories = SubCategory::where('category_id', $category_id)->get();
        return response()->json($subCategories);
    }
}
