@extends('layouts.default')

@section('content')
    <div class="page-title-container max-w-full text-left px-3 mb-3">
        <h1 class="text-2xl font-medium">Declared Shipment Products</h1>
    </div>

    <div class="container flex flex-row max-w-full">


        {{-- START - TICKET PAGE CONTENT --}}

        <div class="container-fluid w-full py-3 px-0">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
 
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id= "table-id">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 hidden">Order ID</th>
                            <th scope="col" class="px-6 py-3">Customer ID</th>
                            <th scope="col" class="px-6 py-3">Customer Name</th>
                            <th scope="col" class="px-6 py-3">Customer Email</th>
                            <th scope="col" class="px-6 py-3">Product Name/Link</th>
                            <th scope="col" class="px-6 py-3">Shipping Method</th>
                            <th scope="col" class="px-6 py-3">Request Type</th>
                            <th scope="col" class="px-6 py-3">Date and Time</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer_tickets as $index => $customer_ticket)
                            <tr
                                class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 hidden">#{{ $index + 1 }}</td> <!-- Display Order ID as row number -->
                                <td class="px-6 py-4">#{{ $customer_ticket['customer_shopify_id'] ?? '' }}</td>
                                <td class="px-6 py-4">
                                    {{ $customer_ticket['customer']->first_name ?? '' }}
                                    {{ $customer_ticket['customer']->last_name ?? '' }}
                                </td>
                                <td class="px-6 py-4">{{ $customer_ticket['customer']->email ?? '' }}</td>
                                <td class="px-6 py-4">
                                    <ul>
                                        @foreach ($customer_ticket['products'] as $product)
                                            <li>

                                                <a href="{{ $product->product_link }}" target="_blank"
                                                    class="d-block px-2 py-1 transition-all hover:bg-red-50 hover:text-gray-900 hover:bg-gray-900">
                                                    <i class="mdi mdi-open-in-new"></i> 
                                                    {{ $product->product_name ?? '' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4">
                                    @if (ucfirst($customer_ticket['shipping_method']) == 'Sea-cargo')
                                        <span class="mdi mdi-ferry"></span>
                                    @else
                                        <span class="mdi mdi-airplane"></span>
                                    @endif
                                    {{ ucfirst($customer_ticket['shipping_method']) }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        // Split the string into an array using the comma as the delimiter
                                        $request_method_array = explode(',', $customer_ticket['request_method']);

                                        // Now you have $request_method_array as an array
                                        // You can use it as needed, for example, to remove duplicates
                                        $unique_request_method = array_unique($request_method_array);

                                        // Now you can implode the unique array if needed
                                        $r_method = implode(',', $unique_request_method);
                                    @endphp
                                    {{ ucfirst(strtolower($r_method)) == 'Request_estimates' ? 'Request Estimate' : 'Declared Estimate' }}

                                </td>
                                <td class="px-6 py-4">
                                    @if ($customer_ticket['customer']->DeclaredProducts->isNotEmpty())
                                        {{ $customer_ticket['customer']->DeclaredProducts->sortByDesc('created_at')->first()->created_at->format('m/d/Y | H:i') }}
                                    @else
                                        <h3>No data available</h3>
                                    @endif
                                </td>
                                <td class="px-6 py-4">  

                                    <p>ticketID: {{$customer_ticket['ticket_id']}}</p>
                                    <p>orderID: {{ $customer_ticket['order_id'] }}</p>

                                    @if($customer_ticket['ticket_id'] && ($customer_ticket['order_id'] == $index + 1) && $customer_ticket['status'] == 'closeTicket')
                                        <a class="btn btn-danger h-full w-full" >
                                            <span class="mdi mdi-close-thick"></span>
                                            Ticket Closed
                                        </a>  
                                        
                                    @else
                                        @if ($customer_ticket['ticket_id'] && ($customer_ticket['order_id'] == $index + 1))
                                            <a class="btn btn-success w-full"
                                                href="{{ route('view_ticket', ['customer_id' => $customer_ticket['customer']->id, 'ticket_id' => $customer_ticket['ticket_id']]) }}">
                                                <span class="mdi mdi-ticket-account" aria-hidden="true"></span>
                                                View Ticket
                                            </a>
                                        @else
                                            <form method="POST" id="assignTicketForm_{{ $loop->iteration }}"
                                                action="{{ route('assign_ticket', ['customer_id' => $customer_ticket['customer']->id, 'ticket_id' => 'assign_ticket_number']) }}"
                                                style="display:inline;">
                                                @csrf

                                                <input type="hidden" name="shipping_method" value="{{ $customer_ticket['shipping_method'] }}">
                                                <input type="hidden" name="request_method" value="{{ $r_method }}">
                                                <input type="hidden" name="order_id" value="{{ $index + 1 }}">
                                                <input type="hidden" name="customer_name" value="{{ $customer_ticket['customer']->first_name }}">

                                                {{-- Add hidden input fields for product IDs --}}
                                                @foreach ($customer_ticket['products'] as $product)
                                                    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                @endforeach

                                                <button type="button" class="btn btn-primary w-full btn-sm assignTicket"
                                                    data-id="{{ $loop->iteration }}"
                                                    data-name="{{ $customer_ticket['customer']->first_name ?? '' }} {{ $customer_ticket['customer']->last_name ?? '' }}">
                                                    <span class="mdi mdi-delete-empty"></span>
                                                    Assign Ticket
                                                </button>
                                            </form>
                                        @endif

                                        <a class="btn btn-info w-full" href="/calculator">
                                            <span class="mdi me-2 mdi-calculator" aria-hidden="true"></span>
                                            Calculator
                                        </a>
                                        @if ($customer_ticket['status'] && ($customer_ticket['order_id'] == $index + 1))
                                            @switch($customer_ticket['status'])
                                                @case('forReview')
                                                    <a class="btn btn-secondary w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        For Review
                                                    </a>
                                                    @break
                                            
                                                @case('pendingPayment')
                                                    <a class="btn btn-danger w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Pending Payment
                                                    </a>
                                                    @break

                                                @case('pendingShippingPayment')
                                                    <a class="btn btn-danger w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Pending Shipping Payment
                                                    </a>
                                                    @break
                                                    
                                                @case('addingMedia')
                                                    <a class="btn btn-light w-full">
                                                        <span class="mdi mdi-image-outline"></span>
                                                        Adding Media/Images
                                                    </a>
                                                    @break

                                                @case('initialPaymentPaid')
                                                    <a class="btn btn-warning w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Initial Payment Paid
                                                    </a>
                                                    @break

                                                @case('shippingPayment')
                                                    <a class="btn btn-secondary w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Shipping Review
                                                    </a>
                                                    @break

                                                @case('shippingPaymentPaid')
                                                    <a class="btn btn-dark w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Shipping Payment Paid
                                                    </a>
                                                    @break

                                                @case('addingTracking')
                                                    <a class="btn btn-secondary w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        For Adding Tracking Code
                                                    </a>
                                                    @break



                                                @case('trackingCodeAdded')
                                                    <a class="btn btn-success w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Tracking Code Added
                                                    </a>
                                                    @break

                                                @case('confirmClosingTicket')
                                                    <a class="btn btn-danger w-full">
                                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                                        Confirm Closing Ticket
                                                    </a>
                                                    @break

                                                    
                                            
                                                @default
                                                    {{-- Handle default case if needed --}}
                                            @endswitch 
                                        @endif
                                    @endif



                                

                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>

 
            </div>
        </div>

        {{-- END - TICKET PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent



    {{-- ASSIGN TICKET SWAL --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.assignTicket').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                var customer_name = this.getAttribute('data-name');
                Swal.fire({
                    title: 'Do you want to create a ticket for this <span style="color:red;">' +
                        customer_name + '</span>?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Create it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('assignTicketForm_' + id).submit();
                    }
                })
            });
        });
    </script>

    {{-- SEARCH BAR FOR TABLE --}}
    <script>
 
    </script>
@endsection
