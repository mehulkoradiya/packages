<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\Classroom;

class ClassroomController extends BaseController
{
    public function index()
    {
        $classrooms = Classroom::orderBy('name')->paginate(15);
        return view('nms::classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('nms::classrooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        Classroom::create($data);
        return redirect()->route('classrooms.index')->with('success', 'Classroom created');
    }

    public function show(Classroom $classroom)
    {
        return view('nms::classrooms.show', compact('classroom'));
    }

    public function edit(Classroom $classroom)
    {
        return view('nms::classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $classroom->update($data);
        return redirect()->route('classrooms.index')->with('success', 'Classroom updated');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')->with('success', 'Classroom deleted');
    }
}
