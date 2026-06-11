<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Models\HomePageImageTag;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $HomePageImageTag = HomePageImageTag::all();
        $gallerys = Gallery::latest()->paginate(10);
        return view('admin.gallery.index',compact('gallerys','HomePageImageTag'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request)
    {
        $requestData = $request->validated();
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/gallery', $imageName);
            $requestData['image'] = $imageName;
        }else {
            $requestData['image'] = 'default.jpg';
        }
        
        Gallery::create($requestData);
        
        return redirect()->route('admin.gallery.index')->with('success', 'Rasm qo\'shildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, string $id)
    {
        $gallery = Gallery::findOrFail($id);
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/gallery', $imageName);
            $requestData['image'] = $imageName;

            if ($gallery->image && $gallery->image !== 'default.jpg') {
                Storage::delete('public/gallery/' . $gallery->image);
            }
        }

        $gallery->update($requestData);

        return redirect()->route('admin.gallery.index')->with('success', 'Rasm yangilandi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        if ($gallery->image && $gallery->image !== 'default.jpg') {
            Storage::delete('public/gallery/' . $gallery->image);
        }
        
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Rasm o\'chirildi.');
    }
}
