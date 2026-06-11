<?php

namespace App\Http\Controllers;

use App\Models\CategoryTop;
use Illuminate\Http\Request;

class CategoryTopController extends Controller
{
    public function index()
    {
        $categoryTop = CategoryTop::paginate(15);
        return view('admin.categoryTop.index', compact('categoryTop'));
    }

    public function create()
    {
        return view('admin.categoryTop.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'url'     => 'required|string|max:255',
        ]);

        CategoryTop::create($validated);
        return redirect()->route('admin.CategoryTop.index')->with('success', 'Yuqori kategoriya yaratildi!');
    }

    public function show(string $id)
    {
        $category = CategoryTop::findOrFail($id);
        return view('admin.CategoryTop.show', compact('category'));
    }

    public function edit(string $id)
    {
        $category = CategoryTop::findOrFail($id);
        return view('admin.CategoryTop.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'url'     => 'required|string|max:255',
        ]);

        CategoryTop::findOrFail($id)->update($validated);
        return redirect()->route('admin.CategoryTop.index')->with('success', 'Yuqori kategoriya yangilandi!');
    }

    public function destroy(string $id)
    {
        CategoryTop::findOrFail($id)->delete();
        return redirect()->route('admin.CategoryTop.index')->with('success', 'Yuqori kategoriya o\'chirildi!');
    }
}
