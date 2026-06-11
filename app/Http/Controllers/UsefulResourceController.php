<?php

namespace App\Http\Controllers;

use App\Models\UsefulResource;
use App\Http\Requests\StoreUsefulResourceRequest;
use App\Http\Requests\UpdateUsefulResourceRequest;
use Illuminate\Support\Facades\Storage;

class UsefulResourceController extends Controller
{
    public function index()
    {
        $usefulResources = UsefulResource::paginate(12);
        return view('admin.usefulResource.index', compact('usefulResources'));
    }

    public function create()
    {
        return view('admin.usefulResource.create');
    }

    public function store(StoreUsefulResourceRequest $request)
    {
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/useful-resources', $imageName);
            $requestData['image'] = $imageName;
        } else {
            $requestData['image'] = 'default.jpg';
        }

        UsefulResource::create($requestData);
        return redirect()->route('admin.usefulResource.index')->with('success', 'Foydali resurs qo\'shildi!');
    }

    public function show(string $id)
    {
        $resource = UsefulResource::findOrFail($id);
        return view('admin.usefulResource.show', compact('resource'));
    }

    public function edit(string $id)
    {
        $resource = UsefulResource::findOrFail($id);
        return view('admin.usefulResource.edit', compact('resource'));
    }

    public function update(UpdateUsefulResourceRequest $request, string $id)
    {
        $resource = UsefulResource::findOrFail($id);
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/useful-resources', $imageName);
            $requestData['image'] = $imageName;

            if ($resource->image && $resource->image !== 'default.jpg') {
                Storage::delete('public/useful-resources/' . $resource->image);
            }
        }

        $resource->update($requestData);
        return redirect()->route('admin.usefulResource.index')->with('success', 'Foydali resurs yangilandi!');
    }

    public function destroy(string $id)
    {
        $resource = UsefulResource::findOrFail($id);

        if ($resource->image && $resource->image !== 'default.jpg') {
            Storage::delete('public/useful-resources/' . $resource->image);
        }

        $resource->delete();
        return redirect()->route('admin.usefulResource.index')->with('success', 'Foydali resurs o\'chirildi!');
    }
}
