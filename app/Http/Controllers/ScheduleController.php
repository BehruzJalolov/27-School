<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\SmenaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function index()
    {
        $schudeli = Schedule::with(['smena', 'lesson'])->paginate(15);
        return view('admin.schudeli.index', compact('schudeli'));
    }

    public function create()
    {
        $smenatype = SmenaType::all();
        $lessons   = Lesson::all();
        return view('admin.schudeli.create', compact('smenatype', 'lessons'));
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'smena_id'  => 'required|exists:smena_types,id',
            'lesson_id' => 'required|exists:lessons,id',
            'week_day'  => 'required|string|max:20',
            'room'      => 'required|string|max:50',
            'time'      => 'required|string|max:20',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/schedule', $imageName);
            $requestData['image'] = $imageName;
        } else {
            $requestData['image'] = 'default.jpg';
        }

        Schedule::create($requestData);
        return redirect()->route('admin.schedule.index')->with('success', 'Dars jadvali qo\'shildi!');
    }

    public function show(string $id)
    {
        $schedule = Schedule::with(['smena', 'lesson'])->findOrFail($id);
        return view('admin.schudeli.show', compact('schedule'));
    }

    public function edit(string $id)
    {
        $schedule  = Schedule::findOrFail($id);
        $smenatype = SmenaType::all();
        $lessons   = Lesson::all();
        return view('admin.schudeli.edit', compact('schedule', 'smenatype', 'lessons'));
    }

    public function update(Request $request, string $id)
    {
        $schedule    = Schedule::findOrFail($id);
        $requestData = $request->validate([
            'smena_id'  => 'required|exists:smena_types,id',
            'lesson_id' => 'required|exists:lessons,id',
            'week_day'  => 'required|string|max:20',
            'room'      => 'required|string|max:50',
            'time'      => 'required|string|max:20',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/schedule', $imageName);
            $requestData['image'] = $imageName;

            if ($schedule->image && $schedule->image !== 'default.jpg') {
                Storage::delete('public/schedule/' . $schedule->image);
            }
        }

        $schedule->update($requestData);
        return redirect()->route('admin.schedule.index')->with('success', 'Dars jadvali yangilandi!');
    }

    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);

        if ($schedule->image && $schedule->image !== 'default.jpg') {
            Storage::delete('public/schedule/' . $schedule->image);
        }

        $schedule->delete();
        return redirect()->route('admin.schedule.index')->with('success', 'Dars jadvali o\'chirildi!');
    }
}
