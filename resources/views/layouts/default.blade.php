<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/calif-logo.png') }}">
    <script src="https://unpkg.com/turbolinks"></script>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @yield('styles')
</head>

<body>
    <div class="app-wrapper">
        <div class="app-content">
            <main role="main">
                <div class="container flex flex-row max-w-full">

                    {{-- SIDEBAR --}}
                    <x-sidebar-menu />

                    {{-- START - PAGE ~ CONTENT --}}
                    <div class="content_container w-full p-5">
                        @if (Breadcrumbs::exists())
                            {!! Breadcrumbs::render() !!}
                        @endif

                        @yield('content')
                    </div>
                    {{-- END - PAGE ~ CONTENT --}}

                </div>


            </main>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
