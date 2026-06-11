<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryChild;
use Illuminate\Http\Request;

class ChildrenCategoryController extends Controller
{
    public function index()
    {
        $childrens = CategoryChild::with('category')->paginate(15);
        return view('admin.categorychildren.index', compact('childrens'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categorychildren.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_uz'     => 'required|string|max:255',
            'name_ru'     => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'url'         => 'required|string|max:255',
        ]);

        CategoryChild::create($validated);
        return redirect()->route('admin.categorychildren.index')->with('success', 'Pastki kategoriya qo\'shildi!');
    }

    public function show(string $id)
    {
        $child = CategoryChild::with('category')->findOrFail($id);
        return view('admin.categorychildren.show', compact('child'));
    }

    public function edit(string $id)
    {
        $category   = CategoryChild::findOrFail($id);
        $categories = Category::all();
        return view('admin.categorychildren.edit', compact('category', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name_uz'     => 'required|string|max:255',
            'name_ru'     => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'url'         => 'required|string|max:255',
        ]);

        CategoryChild::findOrFail($id)->update($validated);
        return redirect()->route('admin.categorychildren.index')->with('success', 'Pastki kategoriya yangilandi!');
    }

    public function destroy(string $id)
    {
        CategoryChild::destroy($id);
        return redirect()->route('admin.categorychildren.index')->with('success', 'Pastki kategoriya o\'chirildi!');
    }
}
