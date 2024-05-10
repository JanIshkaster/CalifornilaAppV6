@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">

        {{-- START - INDEX ~ ASHBOARD CONTENT --}}
        <x-dashboard />
        {{-- END - INDEX ~ ASHBOARD CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
