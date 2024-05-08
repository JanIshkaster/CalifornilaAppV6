<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/calif-logo.png') }}">
    <script src="https://unpkg.com/turbolinks"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css"
        integrity="sha512-/k658G6UsCvbkGRB3vPXpsPHgWeduJwiWGPCGS14IQw3xpr63AEMdA8nMYG2gmYkXitQxDTn6iiK/2fD4T87qA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @yield('styles')
</head>

<body>
    <div class="app-wrapper">
        <div class="app-content">
            <main role="main">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            

                <div class="container flex flex-row max-w-full p-0 h-dvh">



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