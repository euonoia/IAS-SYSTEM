{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ken Burat CRUD')</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom Styles -->
<style>
    body {
        background-color: #f4f6f9;
        font-family: Arial, Helvetica, sans-serif;
    }

    .navbar {
        background-color: #0d6efd;
    }

    .navbar-brand,
    .nav-link,
    .navbar-text {
        color: white !important;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .table th {
        background-color: #f8f9fa;
    }
</style>
</head>
<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('ken-burat.index') }}">
            Ken Burat System
        </a>
    <div class="ms-auto">
        <span class="navbar-text">
            Laravel CRUD Example
        </span>
    </div>
</div>


</nav>

<!-- Main Content -->

<div class="container mt-5">


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card p-4">
    @yield('content')
</div>


</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
