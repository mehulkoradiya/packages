@extends('nms::layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Classrooms</h3>
  <a href="{{ route('classrooms.create') }}" class="btn btn-primary">Add Classroom</a>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr><th>Name</th><th>Section</th><th>Capacity</th><th></th></tr>
  </thead>
  <tbody>
  @foreach($classrooms as $room)
    <tr>
      <td>{{ $room->name }}</td>
      <td>{{ $room->section }}</td>
      <td>{{ $room->capacity }}</td>
      <td>
        <a href="{{ route('classrooms.edit', $room) }}" class="btn btn-sm btn-secondary">Edit</a>
        <form class="d-inline" method="POST" action="{{ route('classrooms.destroy', $room) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $classrooms->links() }}
@endsection
