@extends('nms::layouts.app')

@section('content')
<h3>Edit Classroom</h3>
<form method="POST" action="{{ route('classrooms.update', $classroom) }}" class="card p-3">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" value="{{ $classroom->name }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Section</label>
      <input name="section" class="form-control" value="{{ $classroom->section }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Capacity</label>
      <input type="number" name="capacity" class="form-control" value="{{ $classroom->capacity }}">
    </div>
    <div class="col-12">
      <label class="form-label">Notes</label>
      <textarea name="notes" class="form-control" rows="3">{{ $classroom->notes }}</textarea>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('classrooms.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
