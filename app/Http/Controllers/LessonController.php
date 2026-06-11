<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::paginate(15);
        return view('admin.lesson.index', compact('lessons'));
    }

    public function create()
    {
        return view('admin.lesson.create');
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
        ]);
        Lesson::create($requestData);
        return redirect()->route('admin.lesson.index')->with('success', 'Dars qo\'shildi!');
    }

    public function show(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lesson.show', compact('lesson'));
    }

    public function edit(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lesson.edit', compact('lesson'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
        ]);

        Lesson::findOrFail($id)->update($validated);
        return redirect()->route('admin.lesson.index')->with('success', 'Dars yangilandi!');
    }

    public function destroy(string $id)
    {
        Lesson::destroy($id);
        return redirect()->route('admin.lesson.index')->with('success', 'Dars o\'chirildi!');
    }
}
