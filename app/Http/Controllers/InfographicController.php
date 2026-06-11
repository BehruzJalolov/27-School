<?php

namespace App\Http\Controllers;

use App\Models\Infographic;
use App\Models\HomePageImageTag;
use App\Http\Requests\StoreInfographicRequest;
use App\Http\Requests\UpdateInfographicRequest;
use Illuminate\Support\Facades\Storage;

class InfographicController extends Controller
{
    public function index()
    {
        $HomePageImageTag = HomePageImageTag::all();
        $infoGrafika = Infographic::latest()->paginate(12);
        return view('admin.infografika.index', compact('infoGrafika', 'HomePageImageTag'));
    }

    public function create()
    {
        return view('admin.infografika.create');
    }

    public function store(StoreInfographicRequest $request)
    {
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/infographics', $imageName);
            $requestData['image'] = $imageName;
        } else {
            $requestData['image'] = 'default.jpg';
        }

        Infographic::create($requestData);
        return redirect()->route('admin.infografika.index')->with('success', 'Infografika qo\'shildi!');
    }

    public function show(string $id)
    {
        $infographic = Infographic::findOrFail($id);
        return view('admin.infografika.show', compact('infographic'));
    }

    public function edit(string $id)
    {
        $infographic = Infographic::findOrFail($id);
        return view('admin.infografika.edit', compact('infographic'));
    }

    public function update(UpdateInfographicRequest $request, string $id)
    {
        $infographic = Infographic::findOrFail($id);
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/infographics', $imageName);
            $requestData['image'] = $imageName;

            if ($infographic->image && $infographic->image !== 'default.jpg') {
                Storage::delete('public/infographics/' . $infographic->image);
            }
        }

        $infographic->update($requestData);
        return redirect()->route('admin.infografika.index')->with('success', 'Infografika yangilandi!');
    }

    public function destroy(string $id)
    {
        $infographic = Infographic::findOrFail($id);

        if ($infographic->image && $infographic->image !== 'default.jpg') {
            Storage::delete('public/infographics/' . $infographic->image);
        }

        $infographic->delete();
        return redirect()->route('admin.infografika.index')->with('success', 'Infografika o\'chirildi!');
    }
}
