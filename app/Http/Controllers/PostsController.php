<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::query()
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('title_uz', 'like', "%{$query}%")
                        ->orWhere('title_ru', 'like', "%{$query}%")
                        ->orWhere('body_uz', 'like', "%{$query}%")
                        ->orWhere('body_ru', 'like', "%{$query}%");
                });
            })
            ->paginate(10);

        return view('posts.search-results', compact('posts', 'query'));
    }

    public function index()
    {
        $posts = Post::latest()->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/posts', $imageName);
            $requestData['image'] = $imageName;
        } else {
            $requestData['image'] = 'default.jpg';
        }

        Post::create($requestData);

        return redirect()->route('admin.posts.index')->with('success', 'Post yaratildi.');
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/posts', $imageName);
            $requestData['image'] = $imageName;

            if ($post->image && $post->image !== 'default.jpg') {
                Storage::delete('public/posts/' . $post->image);
            }
        }

        $post->update($requestData);

        return redirect()->route('admin.posts.index')->with('success', 'Post yangilandi.');
    }

    public function destroy(Post $post)
    {
        if ($post->image && $post->image !== 'default.jpg') {
            Storage::delete('public/posts/' . $post->image);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post o\'chirildi.');
    }
}
