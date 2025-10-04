@extends('nms::layouts.app')

@section('content')
<h3>Invoice {{ $invoice->number }}</h3>
<div class="card p-3 mb-3">
  <div class="row">
    <div class="col-md-6">
      <div class="fw-bold">Student</div>
      <div>{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</div>
    </div>
    <div class="col-md-6 text-md-end">
      <div>Issue: {{ $invoice->issue_date->format('Y-m-d') }}</div>
      <div>Due: {{ $invoice->due_date->format('Y-m-d') }}</div>
      <div>Status: <span class="badge bg-{{ $invoice->status==='paid'?'success':($invoice->status==='partially_paid'?'warning':'secondary') }}">{{ str_replace('_',' ', ucfirst($invoice->status)) }}</span></div>
    </div>
  </div>
</div>
<div class="card p-3 mb-3">
  <table class="table">
    <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Total</th></tr></thead>
    <tbody>
      @foreach($invoice->items as $item)
        <tr>
          <td>{{ $item->description }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ number_format($item->unit_price, 2) }}</td>
          <td>{{ number_format($item->line_total, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr><th colspan="3" class="text-end">Subtotal</th><th>{{ number_format($invoice->subtotal, 2) }}</th></tr>
      <tr><th colspan="3" class="text-end">Discount</th><th>-{{ number_format($invoice->discount, 2) }}</th></tr>
      <tr><th colspan="3" class="text-end">Tax</th><th>{{ number_format($invoice->tax, 2) }}</th></tr>
      <tr><th colspan="3" class="text-end">Total</th><th>{{ number_format($invoice->total, 2) }}</th></tr>
    </tfoot>
  </table>
</div>
<div class="card p-3 mb-3">
  <h5>Record Payment</h5>
  <form method="POST" action="{{ route('nms.payments.store', $invoice) }}" class="row g-2">
    @csrf
    <div class="col-md-3"><input class="form-control" name="amount" type="number" step="0.01" placeholder="Amount" required></div>
    <div class="col-md-3"><input class="form-control" name="provider" placeholder="Provider (optional)"></div>
    <div class="col-md-3"><input class="form-control" name="provider_ref" placeholder="Provider Ref (optional)"></div>
    <div class="col-md-3"><input class="form-control" name="paid_at" type="datetime-local"></div>
    <div class="col-12"><button class="btn btn-success">Add Payment</button></div>
  </form>
</div>
<div class="d-flex gap-2">
  <form method="POST" action="{{ route('nms.invoices.send', $invoice) }}">@csrf<button class="btn btn-primary">Send Invoice</button></form>
  <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
