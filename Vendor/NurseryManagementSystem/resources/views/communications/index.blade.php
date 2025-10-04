@extends('nms::layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Communications</h3>
  <a href="{{ route('nms.comm.create') }}" class="btn btn-primary">New Batch</a>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr><th>ID</th><th>Channel</th><th>Subject</th><th>Total</th><th>Sent</th><th></th></tr>
  </thead>
  <tbody>
  @foreach($batches as $batch)
    <tr>
      <td>{{ $batch->id }}</td>
      <td>{{ strtoupper($batch->channel) }}</td>
      <td>{{ $batch->subject }}</td>
      <td>{{ $batch->total }}</td>
      <td>{{ $batch->sent }}</td>
      <td><a href="{{ route('nms.comm.show', $batch) }}" class="btn btn-sm btn-info">View</a></td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $batches->links() }}
@endsection
