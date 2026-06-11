<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryChild;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->paginate(15);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categoryChildren = CategoryChild::all();
        return view('admin.category.create', compact('categoryChildren'));
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('admin.category.index')->with('success', 'Kategoriya muvaffaqiyatli yaratildi!');
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.show', compact('category'));
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return redirect()->route('admin.category.index')->with('success', 'Kategoriya yangilandi!');
    }

    public function destroy(string $id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.category.index')->with('success', 'Kategoriya o\'chirildi!');
    }
}
