<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('nms.ui.brand_name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --bs-primary: {{ config('nms.ui.theme.primary') }};
      --bs-secondary: {{ config('nms.ui.theme.secondary') }};
      --nms-accent: {{ config('nms.ui.theme.accent') }};
      --nms-success: {{ config('nms.ui.theme.success') }};
    }
    .brand-bg { background: linear-gradient(90deg, var(--bs-primary), var(--bs-secondary)); }
    .accent-badge { background-color: var(--nms-accent); }
  </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark brand-bg">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="{{ route('nms.dashboard') }}">{{ config('nms.ui.brand_name') }}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nmsNav"><span class="navbar-toggler-icon"></span></button>
    <div id="nmsNav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('classrooms.index') }}">Classrooms</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}">Students</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('nms.attendance.index') }}">Attendance</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('invoices.index') }}">Invoices</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('nms.comm.index') }}">Communications</a></li>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
