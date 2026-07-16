<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>@yield('title', 'ThaiSilk')</title>

    <meta
        name="description"
        content="@yield('meta_description', 'タイシルクの伝統と現代的なデザインを融合したThaiSilk公式サイトです。')"
    >

    {{-- Google Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap 5 CSS --}}
    <link
        href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

    {{-- Custom CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}"
    >

    @stack('styles')

     @yield('css')
</head>

<body class="@yield('body-class')">

    {{-- Header --}}
    @include('frontend.partials.header')

    {{-- Main Content --}}
    <main id="mainContent">
        @yield('content')
    </main>

    {{-- Bootstrap 5 JavaScript --}}
    <script
        src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"
    ></script>

    @stack('scripts')
 @yield('js')
</body>

</html>