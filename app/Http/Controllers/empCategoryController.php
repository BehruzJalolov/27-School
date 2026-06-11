<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EmpCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmpCategoryRequest;
use App\Http\Requests\UpdateEmpCategoryRequest;

class EmpCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empCategorys = EmpCategory::latest()->paginate(10);
        return view('admin.empCategory.index',compact('empCategorys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.empCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmpCategoryRequest $request)
    {
        $requestData = $request->validated();
        EmpCategory::create($requestData);
        return redirect()->route('admin.empCategory.index')->with('success', 'Kategoriya qo\'shildi');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empCategory = EmpCategory::findOrFail($id);
        return view("admin.empCategory.show",compact('empCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empCategory = EmpCategory::findOrFail($id); // Bitta kategoriya olish uchun shunday bo‘lishi kerak
        return view('admin.empCategory.edit', compact('empCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmpCategoryRequest $request, string $id)
    {
        $empCategory = EmpCategory::findOrFail($id);
        $empCategory->update($request->validated());
        return redirect()->route('admin.empCategory.index')->with('success', 'Kategoriya yangilandi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        EmpCategory::destroy($id);
        return redirect()->route('admin.empCategory.index')->with('success', 'Kategoriya o\'chirildi');
    }
}
