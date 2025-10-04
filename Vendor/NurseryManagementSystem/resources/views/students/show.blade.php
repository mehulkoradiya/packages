@extends('nms::layouts.app')

@section('content')
<h3>Student Profile</h3>
<div class="card p-3 mb-3">
  <div class="row">
    <div class="col-md-6">
      <div class="fw-bold">Name</div>
      <div>{{ $student->first_name }} {{ $student->last_name }}</div>
    </div>
    <div class="col-md-6">
      <div class="fw-bold">Classroom</div>
      <div>{{ optional($student->classroom)->name }}</div>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-4"><div class="text-muted">Roll No</div>{{ $student->roll_no }}</div>
    <div class="col-md-4"><div class="text-muted">DOB</div>{{ optional($student->dob)->format('Y-m-d') }}</div>
    <div class="col-md-4"><div class="text-muted">Gender</div>{{ $student->gender }}</div>
  </div>
</div>
<a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
@endsection
