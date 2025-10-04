@extends('nms::layouts.app')

@section('content')
<h3>Classroom - {{ $classroom->name }} @if($classroom->section) ({{ $classroom->section }}) @endif</h3>
<div class="card p-3 mb-3">
  <div class="row">
    <div class="col-md-4"><div class="text-muted">Capacity</div>{{ $classroom->capacity }}</div>
    <div class="col-md-8"><div class="text-muted">Notes</div>{{ $classroom->notes }}</div>
  </div>
</div>
<div class="card p-3">
  <h5>Students</h5>
  <table class="table table-striped">
    <thead><tr><th>Name</th><th>Roll No</th></tr></thead>
    <tbody>
      @foreach($classroom->students as $s)
        <tr>
          <td>{{ $s->first_name }} {{ $s->last_name }}</td>
          <td>{{ $s->roll_no }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
