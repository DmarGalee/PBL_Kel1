<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mazer Admin Dashboard') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('dist/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app-dark.css') }}">
    @stack('css')
</head>

<body>
    <script src="{{ asset('dist/assets/static/js/initTheme.js') }}"></script>
    <div id="app">


        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div id="main" class='layout-navbar navbar-fixed'>
            <!-- Navbar -->
            @include('layouts.header')


            {{-- breadcrumb dan main content --}}
            <div id="main-content">

                <div class="page-heading">

                    {{-- breadcrumb --}}
                    @include('layouts.breadcrumb')

                    {{-- main content --}}
                    <section class="section">
                        @yield('content')
                    </section>


                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('dist/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dist/assets/compiled/js/app.js') }}"></script>

    <!-- CSRF Token Setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('js')
</body>

</html>
