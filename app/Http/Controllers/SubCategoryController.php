<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $subCategories = SubCategory::with('category')->get();
        return view('dashboard.admin.subCategory', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sub_categories,name',
            'category_id' => 'required|exists:categories,id',
        ]);

        SubCategory::create($request->only('name', 'category_id'));

        return redirect()->route('admin.subCategory.index')->with('success', 'Sub Category created successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        $subCategories = SubCategory::with('category')->get();
        return view('dashboard.admin.subCategory', [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'editSubCategory' => $subCategory,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:sub_categories,name,' . $subCategory->id,
            'category_id' => 'required|exists:categories,id',
        ]);

        $subCategory->update($request->only('name', 'category_id'));

        return redirect()->route('admin.subCategory.index')->with('success', 'Sub Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        try {
            $subCategory->delete();
            return redirect()->route('admin.subCategory.index')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.subCategory.index')->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
