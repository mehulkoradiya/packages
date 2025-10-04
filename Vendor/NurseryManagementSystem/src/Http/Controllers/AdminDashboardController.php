<?php

namespace Vendor\NurseryManagementSystem\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Vendor\NurseryManagementSystem\Models\Classroom;
use Vendor\NurseryManagementSystem\Models\Student;
use Vendor\NurseryManagementSystem\Models\Attendance;
use Vendor\NurseryManagementSystem\Models\Invoice;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        $stats = [
            'classrooms' => Classroom::count(),
            'students' => Student::count(),
            'attendance_today' => Attendance::where('date', now()->toDateString())->count(),
            'unpaid_invoices' => Invoice::where('status', 'unpaid')->count(),
        ];

        return view('nms::dashboard.index', compact('stats'));
    }
}
