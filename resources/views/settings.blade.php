@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">

        {{-- START - SETTINGS PAGE CONTENT --}}
        <div class="container-fluid max-w-full p-5">
            <h1>TEST SETTINGS</h1>
            <p>currentURL: {{ route('settings_form') }}</p>

            {{-- Check if formData is set --}}
            @isset($formData)
                <div>
                    <h2>Form Data</h2>
                    <p>Name: {{ $formData['name'] }}</p>
                    <!-- Display other form data as needed -->
                </div>
            @endisset

            <form action="{{ route('settings_form') }}" method="post">
                @csrf
                <input type="hidden" name="sessionToken" class="session-token">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name"><br>
                <button type="submit">submit</button>
            </form>
        </div>
        {{-- END - SETTINGS PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
