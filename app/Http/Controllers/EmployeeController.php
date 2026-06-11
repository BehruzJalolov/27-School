<?php

namespace App\Http\Controllers;

use App\Models\EmpCategory;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $employees = Employee::with(['category', 'position'])->paginate(15);
         return view('admin.employee.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empCategories = EmpCategory::all();
        $positions = Position::all();
        return view('admin.employee.create',compact('positions','empCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $requestData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/employees', $imageName);
            $requestData['image'] = $imageName;
        } else {
            $requestData['image'] = 'default.jpg';
        }

        Employee::create($requestData);

        return redirect()->route('admin.employee.index')->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified resource.
     */
  public function show($id)
{
    $employee = Employee::with(['category', 'position'])->findOrFail($id);
    return view('admin.employee.show', compact('employee'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empCategories = EmpCategory::all();
        $positions = Position::all();
        $employee = Employee::findOrFail($id);
        return view('admin.employee.edit', compact('employee', 'positions', 'empCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/employees', $fileName);
            $data['image'] = $fileName;

            if ($employee->image && $employee->image !== 'default.jpg') {
                Storage::delete('public/employees/' . $employee->image);
            }
        }

        $employee->update($data);

        return redirect()->route('admin.employee.index')->with('success', 'Employee updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        
        if ($employee->image && $employee->image !== 'default.jpg') {
            Storage::delete('public/employees/' . $employee->image);
        }
        
        $employee->delete();
        
        return redirect()->route('admin.employee.index')->with('success', 'Employee deleted successfully!');
    }
}
