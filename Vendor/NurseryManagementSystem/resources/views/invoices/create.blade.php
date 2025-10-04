@extends('nms::layouts.app')

@section('content')
<h3>Create Invoice</h3>
<form method="POST" action="{{ route('invoices.store') }}" class="card p-3">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Student</label>
      <select name="student_id" class="form-select" required>
        @foreach($students as $s)
          <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Issue Date</label>
      <input type="date" name="issue_date" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Due Date</label>
      <input type="date" name="due_date" value="{{ now()->addDays(14)->format('Y-m-d') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Currency</label>
      <input name="currency" class="form-control" value="USD" required>
    </div>
    <div class="col-12">
      <label class="form-label">Items</label>
      <div id="items">
        <div class="row g-2 align-items-end mb-2">
          <div class="col-md-6"><input name="items[0][description]" class="form-control" placeholder="Description" required></div>
          <div class="col-md-2"><input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required></div>
          <div class="col-md-2"><input type="number" step="0.01" name="items[0][unit_price]" class="form-control" value="0.00" min="0" required></div>
        </div>
      </div>
    </div>
    <div class="col-md-3"><label class="form-label">Discount</label><input type="number" step="0.01" name="discount" class="form-control" value="0"></div>
    <div class="col-md-3"><label class="form-label">Tax</label><input type="number" step="0.01" name="tax" class="form-control" value="0"></div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Create</button>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
