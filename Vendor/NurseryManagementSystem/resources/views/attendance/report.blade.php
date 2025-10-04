@extends('nms::layouts.app')

@section('content')
<h3>Attendance Report</h3>
<form method="GET" class="card p-3 mb-3">
  <div class="row g-3">
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" name="from" value="{{ $from }}" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" name="to" value="{{ $to }}" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Classroom</label>
      <select name="classroom_id" class="form-select">
        <option value="">All</option>
        @php $rooms = \Vendor\NurseryManagementSystem\Models\Classroom::orderBy('name')->get(); @endphp
        @foreach($rooms as $room)
          <option value="{{ $room->id }}" @if($classroomId==$room->id) selected @endif>{{ $room->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button class="btn btn-primary">Filter</button>
    </div>
  </div>
</form>
<table class="table table-bordered table-striped">
  <thead><tr><th>Date</th><th>Student</th><th>Classroom</th><th>Status</th></tr></thead>
  <tbody>
  @foreach($records as $r)
    <tr>
      <td>{{ $r->date->format('Y-m-d') }}</td>
      <td>{{ $r->student->first_name }} {{ $r->student->last_name }}</td>
      <td>{{ optional($r->student->classroom)->name }}</td>
      <td>{{ ucfirst($r->status) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $records->links() }}
@endsection
