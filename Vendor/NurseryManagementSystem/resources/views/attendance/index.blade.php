@extends('nms::layouts.app')

@section('content')
<h3>Daily Attendance</h3>
<form method="GET" class="mb-3">
  <div class="row g-2 align-items-end">
    <div class="col-auto">
      <label class="form-label">Date</label>
      <input type="date" name="date" value="{{ $date }}" class="form-control">
    </div>
    <div class="col-auto">
      <button class="btn btn-secondary">Change Date</button>
    </div>
  </div>
</form>

<div class="accordion" id="rooms">
  @foreach($classrooms as $room)
  <div class="accordion-item">
    <h2 class="accordion-header" id="h{{ $room->id }}">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c{{ $room->id }}">{{ $room->name }} @if($room->section) ({{ $room->section }}) @endif</button>
    </h2>
    <div id="c{{ $room->id }}" class="accordion-collapse collapse" data-bs-parent="#rooms">
      <div class="accordion-body">
        @php $students = $room->students()->orderBy('last_name')->get(); @endphp
        <form method="POST" action="{{ route('nms.attendance.storeDaily') }}" class="table-responsive">
          @csrf
          <input type="hidden" name="date" value="{{ $date }}">
          <table class="table align-middle">
            <thead><tr><th>Student</th><th>Status</th><th>Notes</th></tr></thead>
            <tbody>
              @foreach($students as $student)
              <tr>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>
                  <select name="records[{{ $loop->index }}][status]" class="form-select">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="excused">Excused</option>
                  </select>
                  <input type="hidden" name="records[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                </td>
                <td><input name="records[{{ $loop->index }}][notes]" class="form-control" placeholder="Optional"></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <button class="btn btn-primary">Save {{ $room->name }}</button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
