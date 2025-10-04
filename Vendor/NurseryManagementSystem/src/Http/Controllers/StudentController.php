<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\Student;
use Vendor\NurseryManagementSystem\Models\Classroom;

class StudentController extends BaseController
{
    public function index()
    {
        $students = Student::with('classroom')->orderBy('last_name')->paginate(15);
        return view('nms::students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();
        return view('nms::students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'classroom_id' => 'nullable|exists:nms_classrooms,id',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'roll_no' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        Student::create($data);
        return redirect()->route('students.index')->with('success', 'Student created');
    }

    public function show(Student $student)
    {
        $student->load('classroom', 'attendanceRecords');
        return view('nms::students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::orderBy('name')->get();
        return view('nms::students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'classroom_id' => 'nullable|exists:nms_classrooms,id',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'roll_no' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $student->update($data);
        return redirect()->route('students.index')->with('success', 'Student updated');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted');
    }
}
