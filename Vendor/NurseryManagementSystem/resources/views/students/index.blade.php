@extends('nms::layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Students</h3>
  <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr><th>Name</th><th>Classroom</th><th>Roll No</th><th></th></tr>
  </thead>
  <tbody>
  @foreach($students as $student)
    <tr>
      <td>{{ $student->first_name }} {{ $student->last_name }}</td>
      <td>{{ optional($student->classroom)->name }}</td>
      <td>{{ $student->roll_no }}</td>
      <td>
        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-secondary">Edit</a>
        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">View</a>
        <form class="d-inline" method="POST" action="{{ route('students.destroy', $student) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $students->links() }}
@endsection
