@extends('nms::layouts.app')

@section('content')
<h3>New Communication Batch</h3>
<form method="POST" action="{{ route('nms.comm.store') }}" class="card p-3">
  @csrf
  <div class="row g-3">
    <div class="col-md-3">
      <label class="form-label">Channel</label>
      <select name="channel" class="form-select">
        <option value="email">Email</option>
        <option value="sms">SMS</option>
        <option value="whatsapp">WhatsApp</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Subject (for email)</label>
      <input name="subject" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Classroom (optional)</label>
      <select name="classroom_id" class="form-select">
        <option value="">All</option>
        @foreach($classrooms as $room)
          <option value="{{ $room->id }}">{{ $room->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-12">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="6" required></textarea>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Queue Batch</button>
    <a href="{{ route('nms.comm.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
