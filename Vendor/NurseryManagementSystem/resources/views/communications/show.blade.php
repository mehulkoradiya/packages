@extends('nms::layouts.app')

@section('content')
<h3>Batch #{{ $batch->id }} - {{ strtoupper($batch->channel) }}</h3>
<div class="card p-3 mb-3">
  <div>Subject: {{ $batch->subject }}</div>
  <div>Total: {{ $batch->total }} | Sent: {{ $batch->sent }}</div>
</div>
<table class="table table-striped table-bordered">
  <thead><tr><th>ID</th><th>Parent</th><th>Status</th><th>Error</th></tr></thead>
  <tbody>
    @foreach($recipients as $r)
    <tr>
      <td>{{ $r->id }}</td>
      <td>{{ optional($r->parent)->name }}</td>
      <td>{{ $r->status }}</td>
      <td>{{ $r->error }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $recipients->links() }}
@endsection
