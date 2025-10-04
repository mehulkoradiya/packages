<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\Attendance;
use Vendor\NurseryManagementSystem\Models\Student;
use Vendor\NurseryManagementSystem\Models\Classroom;

class AttendanceController extends BaseController
{
    public function index()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $date = request('date', now()->toDateString());
        return view('nms::attendance.index', compact('classrooms', 'date'));
    }

    public function storeDaily(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:nms_students,id',
            'records.*.status' => 'required|in:present,absent,late,excused',
            'records.*.notes' => 'nullable|string',
        ]);

        foreach ($data['records'] as $record) {
            Attendance::updateOrCreate(
                ['student_id' => $record['student_id'], 'date' => $data['date']],
                ['status' => $record['status'], 'notes' => $record['notes'] ?? null]
            );
        }

        return back()->with('success', 'Attendance saved');
    }

    public function showStudent(Student $student)
    {
        $records = $student->attendanceRecords()->orderByDesc('date')->paginate(30);
        return view('nms::attendance.student', compact('student', 'records'));
    }

    public function report()
    {
        $from = request('from');
        $to = request('to');
        $classroomId = request('classroom_id');

        $query = Attendance::query()->with('student.classroom');
        if ($from) { $query->whereDate('date', '>=', $from); }
        if ($to) { $query->whereDate('date', '<=', $to); }
        if ($classroomId) { $query->whereHas('student', fn($q) => $q->where('classroom_id', $classroomId)); }

        $records = $query->orderBy('date')->paginate(50);
        return view('nms::attendance.report', compact('records', 'from', 'to', 'classroomId'));
    }
}
