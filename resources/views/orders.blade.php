@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">


        {{-- START - ORDERS PAGE CONTENT --}}
        <div class="container-fluid w-full p-5">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Id</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Customer</th>
                            <th scope="col" class="px-6 py-3">Total</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Payment Gateway</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($orders as $order)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">#{{ $order['id'] }}</td>
                                <td class="px-6 py-4">{{ date('m-d-Y', strtotime($order['created_at'])) }}</td>
                                <td class="px-6 py-4">{{ $order['email'] }}</td>
                                <td class="px-6 py-4">{{ $order['total_price'] }}</td>
                                <td class="px-6 py-4">{{ $order['fulfillment_status'] }}</td>
                                <td class="px-6 py-4">{{ $order['payment_gateway_names']['title'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">No items found.</td>
                            </tr>
                        @endforelse


                    </tbody>
                </table>
            </div>
        </div>
        {{-- END - ORDERS PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
