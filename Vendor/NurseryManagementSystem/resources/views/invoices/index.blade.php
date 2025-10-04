@extends('nms::layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Invoices</h3>
  <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
</div>
<table class="table table-striped table-bordered">
  <thead>
    <tr><th>#</th><th>Student</th><th>Issue</th><th>Due</th><th>Total</th><th>Status</th><th></th></tr>
  </thead>
  <tbody>
  @foreach($invoices as $inv)
    <tr>
      <td>{{ $inv->number }}</td>
      <td>{{ $inv->student->first_name }} {{ $inv->student->last_name }}</td>
      <td>{{ $inv->issue_date->format('Y-m-d') }}</td>
      <td>{{ $inv->due_date->format('Y-m-d') }}</td>
      <td>{{ $inv->currency }} {{ number_format($inv->total, 2) }}</td>
      <td>
        <span class="badge bg-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'partially_paid' ? 'warning' : 'secondary') }}">{{ str_replace('_',' ', ucfirst($inv->status)) }}</span>
      </td>
      <td>
        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-info">View</a>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $invoices->links() }}
@endsection
