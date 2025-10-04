<?php

use Illuminate\Support\Facades\Route;
use Vendor\NurseryManagementSystem\Http\Controllers\AdminDashboardController;
use Vendor\NurseryManagementSystem\Http\Controllers\ClassroomController;
use Vendor\NurseryManagementSystem\Http\Controllers\StudentController;
use Vendor\NurseryManagementSystem\Http\Controllers\AttendanceController;
use Vendor\NurseryManagementSystem\Http\Controllers\InvoiceController;
use Vendor\NurseryManagementSystem\Http\Controllers\PaymentController;
use Vendor\NurseryManagementSystem\Http\Controllers\CommunicationController;

Route::group([
    'prefix' => config('nms.prefix'),
    'middleware' => array_merge(config('nms.middleware', []), ['nms.role:Admin']),
], function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('nms.dashboard');

    Route::resource('classrooms', ClassroomController::class);
    Route::resource('students', StudentController::class);

    Route::get('attendance', [AttendanceController::class, 'index'])->name('nms.attendance.index');
    Route::post('attendance/record', [AttendanceController::class, 'storeDaily'])->name('nms.attendance.storeDaily');
    Route::get('attendance/student/{student}', [AttendanceController::class, 'showStudent'])->name('nms.attendance.student');
    Route::get('attendance/report', [AttendanceController::class, 'report'])->name('nms.attendance.report');

    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'sendInvoice'])->name('nms.invoices.send');

    Route::post('payments/{invoice}', [PaymentController::class, 'store'])->name('nms.payments.store');
    Route::post('payments/webhook/{provider}', [PaymentController::class, 'webhook'])->name('nms.payments.webhook');

    Route::get('communications', [CommunicationController::class, 'index'])->name('nms.comm.index');
    Route::get('communications/create', [CommunicationController::class, 'create'])->name('nms.comm.create');
    Route::post('communications', [CommunicationController::class, 'store'])->name('nms.comm.store');
    Route::get('communications/{batch}', [CommunicationController::class, 'show'])->name('nms.comm.show');
});
