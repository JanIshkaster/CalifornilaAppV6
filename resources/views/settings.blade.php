@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">

        {{-- START - SETTINGS PAGE CONTENT --}}
        <div class="container-fluid max-w-full p-5">
            <h1 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray mb-5">ADMIN SETTINGS</h1>  

                <form method="POST" id="settings_form" action="{{ route('settings_form') }}" class="w-full">
                    @csrf
                    <div class="settings_container d-flex w-full">
                        <div class="first_col w-full">
                            <div class="max-w-sm mb-3">
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray">Admin</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                        </svg>
                                    </span>
                                    <input type="text" id="website-admin" value="{{ old('admin_emails', $adminSettings->first()->admin_emails ?? '') }}" name="admin_emails" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="admin@californila.com">
                                </div>
                            </div>
                            <div class="max-w-sm mb-3">
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray">Dollar to Pesos Conversion</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        ₱
                                    </span>
                                    <input type="number" step="0.01" value="{{ old('dollar_conversion', $adminSettings->first()->dollar_conversion ?? '') }}" id="website-admin" name="dollar_conversion" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                </div>
                            </div>
                            <div class="max-w-sm mb-3">
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray">Handling Fee</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <span class="mdi mdi-percent-outline"></span>
                                    </span>
                                    <input type="number" step="0.01" value="{{ old('handling_fee', $adminSettings->first()->handling_fee ?? '') }}" id="website-admin" name="handling_fee" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                </div>
                            </div>
                        </div>
                        <div class="second_col w-full">
                            <div class="max-w-sm mb-3">
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray">Credit Card Fee</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <span class="mdi mdi-percent-outline"></span>
                                    </span>
                                    <input type="number" step="0.01" value="{{ old('credit_card_fee', $adminSettings->first()->credit_card_fee ?? '') }}" id="website-admin" name="credit_card_fee" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                </div>
                            </div>
                            <div class="max-w-sm mb-3">
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray">Customs Tax</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <span class="mdi mdi-percent-outline"></span>
                                    </span>
                                    <input type="number" step="0.01" value="{{ old('custom_tax', $adminSettings->first()->custom_tax ?? '') }}" id="website-admin" name="custom_tax" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                </div>
                            </div>
                            <div class="max-w-sm mb-3">
                                <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray">Convenience Fee</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <span class="mdi mdi-percent-outline"></span>
                                    </span>
                                    <input type="number" step="0.01" value="{{ old('convenience_fee', $adminSettings->first()->convenience_fee ?? '') }}" id="website-admin" name="convenience_fee" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                </div>
                            </div>
                        </div> 
                    </div>

                     <!-- Save button -->
                     <button type="submit" id="save-button" class="btn btn-primary" disabled>Save Changes</button>
                </form> 
            
        </div>
        {{-- END - SETTINGS PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('settings_form');
            const saveButton = document.getElementById('save-button');

            form.addEventListener('input', function () {
                // Check if any input element has changed
                const inputsChanged = Array.from(form.elements).some(input => input.type !== 'submit' && input.value !== input.defaultValue);

                // Remove the disabled attribute if inputs have changed
                if (inputsChanged) {
                    saveButton.removeAttribute('disabled');
                    saveButton.classList.remove('btn-primary');
                    saveButton.classList.add('btn-success');
                } else {
                    saveButton.setAttribute('disabled', true);
                    saveButton.classList.remove('btn-success');
                    saveButton.classList.add('btn-primary');
                }
            });
        });
    </script>
@endsection
