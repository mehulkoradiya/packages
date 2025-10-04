@extends('nms::layouts.app')

@section('content')
<h3>New Classroom</h3>
<form method="POST" action="{{ route('classrooms.store') }}" class="card p-3">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Section</label>
      <input name="section" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">Capacity</label>
      <input type="number" name="capacity" class="form-control" value="30">
    </div>
    <div class="col-12">
      <label class="form-label">Notes</label>
      <textarea name="notes" class="form-control" rows="3"></textarea>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('classrooms.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
