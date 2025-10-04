@extends('nms::layouts.app')

@section('content')
<div class="row g-3">
  <div class="col-6 col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <div class="fs-1">{{ $stats['classrooms'] }}</div>
        <div class="text-muted">Classrooms</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <div class="fs-1">{{ $stats['students'] }}</div>
        <div class="text-muted">Students</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <div class="fs-1">{{ $stats['attendance_today'] }}</div>
        <div class="text-muted">Attendance Today</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <div class="fs-1">{{ $stats['unpaid_invoices'] }}</div>
        <div class="text-muted">Unpaid Invoices</div>
      </div>
    </div>
  </div>
</div>
@endsection
