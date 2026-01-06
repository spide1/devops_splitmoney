<!DOCTYPE html>
<html>
<head>
    <title>SplitMoney</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('groups.index') }}">
            💰 SplitMoney
        </a>

        <!-- NO LOGIN, SO SHOW STATIC TEXT -->
        {{-- <span class="text-white small">
            Guest User
        </span> --}}
    </div>
</nav>

<div class="container my-4">
    @yield('content')
</div>

</body>
</html>
