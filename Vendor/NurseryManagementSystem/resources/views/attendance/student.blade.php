@extends('nms::layouts.app')

@section('content')
<h3>Attendance - {{ $student->first_name }} {{ $student->last_name }}</h3>
<table class="table table-striped">
  <thead><tr><th>Date</th><th>Status</th><th>Notes</th></tr></thead>
  <tbody>
    @foreach($records as $r)
      <tr>
        <td>{{ $r->date->format('Y-m-d') }}</td>
        <td><span class="badge bg-{{ $r->status=='present'?'success':($r->status=='late'?'warning':'danger') }}">{{ ucfirst($r->status) }}</span></td>
        <td>{{ $r->notes }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $records->links() }}
@endsection
