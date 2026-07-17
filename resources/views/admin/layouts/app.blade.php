<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>

    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
</head>

<body>
    @yield('css')
    @include('admin.layouts.sidebar')

    <main class="main-content">
        @include('admin.layouts.header')

        <div class="page-content">
            @yield('content')
        </div>
    </main>
    @yield('js')
</body>

</html>
