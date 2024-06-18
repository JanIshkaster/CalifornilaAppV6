@extends('layouts.default')

@section('content')
    <div class="page-title-container max-w-full text-left px-3 mb-3">
        <h1 class="text-2xl font-medium">Solved Tickets</h1>
    </div>

    <div class="container flex flex-row max-w-full">


        {{-- START - SOLVED TICKETS PAGE CONTENT --}}

        <div class="container-fluid w-full py-3 px-0">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
 
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id= "table-id">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr> 
                            <th scope="col" class="px-6 py-3">Ticket ID</th>
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
                        @foreach ($solvedTickets as $ticket)
                            <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">{{ $ticket->ticket_id }}</td>
                                <td class="px-6 py-4">{{ $ticket->customer->first_name }}</td>
                                <td class="px-6 py-4">{{ $ticket->customer->email }}</td>
                                <td class="px-6 py-4"> 
                                    <ul>
                                        @foreach ($ticket->DeclaredProducts as $product)
                                            <li>

                                                <a href="{{ $product->product_link }}" target="_blank"
                                                    class="d-block px-2 py-1 transition-all hover:bg-red-50 hover:text-gray-900 hover:bg-gray-900">
                                                    <i class="mdi mdi-open-in-new"></i>
                                                    #{{ $product->id ?? '' }}
                                                    {{ $product->product_name ?? '' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td> 
                                <td class="px-6 py-4">{{ $ticket->shipping_method }}</td>  
                                <td class="px-6 py-4"> 
                                    @php
                                        // Split the string into an array using the comma as the delimiter
                                        $request_method_array = explode(',', $ticket->request_type);

                                        // Now you have $request_method_array as an array
                                        // You can use it as needed, for example, to remove duplicates
                                        $unique_request_method = array_unique($request_method_array);

                                        // Now you can implode the unique array if needed
                                        $r_method = implode(',', $unique_request_method);
                                    @endphp
                                    {{ ucfirst(strtolower($r_method)) == 'Request_estimates' ? 'Request Estimate' : 'Declared Estimate' }}
                                </td>  
                                <td class="px-6 py-4">{{ $ticket->created_at->format('m/d/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <a class="btn btn-danger h-full w-full"  >
                                        <span class="mdi me-2 mdi-note" aria-hidden="true"></span>
                                        Closed
                                    </a>    
                                </td>  
                            </tr>
                        @endforeach
                    </tbody>
                    


                </table>

 
            </div>
        </div>

        {{-- END - SOLVED TICKETS PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent



    {{-- ASSIGN TICKET SWAL --}}
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </script> --}}

    {{-- SEARCH BAR FOR TABLE --}}
    <script>
 
    </script>
@endsection
