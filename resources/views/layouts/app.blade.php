<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>IT Helpdesk | @yield('title')</title>

    <link href="{{ asset('assets/css/coreui.min.css') }}" rel="stylesheet">
</head>

<body>

    @include('layouts.partials.sidebar')

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        @include('layouts.partials.navbar')
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/coreui.bundle.min.js') }}"></script>

</body>

</html>
