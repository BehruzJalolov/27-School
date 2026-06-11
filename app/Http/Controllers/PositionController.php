<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::paginate(15);
        return view('admin.position.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.position.create');
    }

    public function store(StorePositionRequest $request)
    {
        Position::create($request->validated());
        return redirect()->route('admin.position.index')->with('success', 'Lavozim qo\'shildi!');
    }

    public function show(string $id)
    {
        $position = Position::findOrFail($id);
        return view('admin.position.show', compact('position'));
    }

    public function edit(string $id)
    {
        $position = Position::findOrFail($id);
        return view('admin.position.edit', compact('position'));
    }

    public function update(UpdatePositionRequest $request, string $id)
    {
        $position = Position::findOrFail($id);
        $position->update($request->validated());
        return redirect()->route('admin.position.index')->with('success', 'Lavozim yangilandi!');
    }

    public function destroy(string $id)
    {
        Position::destroy($id);
        return redirect()->route('admin.position.index')->with('success', 'Lavozim o\'chirildi!');
    }
}
