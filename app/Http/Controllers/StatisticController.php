<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use App\Http\Requests\StoreStatisticRequest;
use App\Http\Requests\UpdateStatisticRequest;

class StatisticController extends Controller
{
    public function index()
    {
        $statistics = Statistic::paginate(15);
        return view('admin.statictik.index', compact('statistics'));
    }

    public function create()
    {
        return view('admin.statictik.create');
    }

    public function store(StoreStatisticRequest $request)
    {
        Statistic::create($request->validated());
        return redirect()->route('admin.statictik.index')->with('success', 'Statistika qo\'shildi!');
    }

    public function show(string $id)
    {
        $statistic = Statistic::findOrFail($id);
        return view('admin.statictik.show', compact('statistic'));
    }

    public function edit(string $id)
    {
        $statistic = Statistic::findOrFail($id);
        return view('admin.statictik.edit', compact('statistic'));
    }

    public function update(UpdateStatisticRequest $request, string $id)
    {
        $statistic = Statistic::findOrFail($id);
        $statistic->update($request->validated());
        return redirect()->route('admin.statictik.index')->with('success', 'Statistika yangilandi!');
    }

    public function destroy(string $id)
    {
        Statistic::destroy($id);
        return redirect()->route('admin.statictik.index')->with('success', 'Statistika o\'chirildi!');
    }
}
