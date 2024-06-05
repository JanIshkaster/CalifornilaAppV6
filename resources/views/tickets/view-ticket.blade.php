@extends('layouts.default')
<style>
.alert.alert-success {
    display: none;
}
</style>
@section('content')
 
 

    <div class="container flex flex-row max-w-full">

        {{-- START - ASSIGN TICKET PAGE CONTENT --}}  
        <div class="col-md-7 mx-1">
            <div class="card mb-2">
                <div class="card-header text-md text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    Ticket ID: {{ $ticket_id }}
                    | 
                    <small class="bold">
                        <span class="mdi mdi-account-cog"></span>
                        Admin: {{ Auth::user()->name }}
                    </small>
                </div>
                <div class="card-body p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-b-lg w-full">
                    <h4 class="card-title">Customer Information</h4>
                    <table class="table ticket_table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-3 mb-3">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name </th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Add/View Note</th>
                                <th scope="col" class="px-6 py-3">Additional Fees</th>
                                <th scope="col" class="px-6 py-3">Logs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">
                                    <a href="#" target="blank">
                                        <i class="fas fa-link"></i>
                                        {{ $existing_ticket->Customer->first_name }} {{ $existing_ticket->Customer->last_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">{{ $existing_ticket->Customer->email }}</td>
                                <td class="px-6 py-4">
                                    <div x-data="{ open_add_note: false }">
                                        <button type="button" class="btn btn-primary" @click="open_add_note = !open_add_note">
                                            <span class="mdi mdi-note-alert-outline"></span>
                                        </button>
                                    
                                        {{-- START - ADD NOTE MODAL --}}
                                        <div x-show="open_add_note" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-90"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-90"
                                            x-bind:class="{ 'block': open_add_note, 'hidden': !open_add_note }" 
                                            class="fixed inset-0 flex items-center justify-center z-50 hidden" style="z-index:9999;">
                                    
                                            <!-- Background overlay -->
                                            <div class="absolute inset-0 bg-black opacity-20" @click="open_add_note = false"></div>

                                            <!-- Modal Add Fee form -->
                                            <div class="bg-gray-50 border-white border-solid border text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-b-lg rounded shadow-lg p-8 m-4 w-2/4 max-h-full text-center z-50">
                                                <div class="modal-content">
                                                    <div class="modal-header ">
                                                        <h5 class="modal-title mb-2" id="exampleModalLongTitle" style="font-size: 1.25rem;">
                                                            Submit Information
                                                        </h5> 
                                                    </div>
                                                    <form method="POST" id="note_submit" action="{{ route('store_ticket_note', ['customer_id' => $existing_ticket->Customer->id, 'ticket_id' => $ticket_id]) }}">
                                                        @csrf
                                                        <div class="modal-body overflow-y-scroll h-96">
                                                            <div class="m-2">
                                                                <div class="form-group text-left">
                                                                    <label for="noted_data" class="mb-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white"><strong>Add Note:</strong></label>
                                                                    <textarea class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="ticket_note" id="noted_data" rows="3"></textarea>
                                                                    <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" />
                                                                    <input type="hidden" name="customer_first_name" value="{{ $existing_ticket->Customer->first_name }}" />
                                                                    <input type="hidden" name="customer_id" value="{{ $existing_ticket->Customer->id }}" />
                                                                    <input type="hidden" name="email_type" value="emailNote" />
                                                                </div>
                                                            </div>

                                                            @forelse ($notes as $note)
                                                                <div class="m-2 text-left d-flex" style="border-top: 1px solid #80808054; padding: 10px;">
                                                                    <strong>Note#{{ $loop->iteration }}:</strong>
                                                                    <p class="mx-2">{{ $note->content }}</p>
                                                                </div>
                                                            @empty
                                                                No notes added yet
                                                            @endforelse

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Add Note</button>
                                                            <button type="button" class="btn btn-secondary" @click="open_add_note = false">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- END - ADD NOTE MODAL --}}
                                    </div> 

                                </td>
                                <td class="px-6 py-4 block"> 
                                    {{-- START - ADD FEE MODAL --}}
                                    <div x-data="{ open_add_fee: false }" class="w-full d-flex">  

                                        <button @click="open_add_fee = !open_add_fee" type="button" class="btn btn-primary mx-1" data-toggle="modal" data-target="#add_fees">
                                            <span class="mdi mdi-plus-box"></span>
                                        </button> 

                                        <div x-show="open_add_fee" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-90"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-90"
                                            x-bind:class="{ 'block': open_add_fee, 'hidden': !open_add_fee }" 
                                            class="fixed inset-0 flex items-center justify-center z-50 hidden" style="z-index:9999;">
                                    
                                            <!-- Background overlay -->
                                            <div class="absolute inset-0 bg-black opacity-20" @click="open_add_fee = false"></div>

                                            <!-- Modal Add Fee form -->
                                            <div class="bg-gray-50 border-white border-solid border text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-b-lg rounded shadow-lg p-8 m-4 w-2/4 max-h-full text-center z-50 ">
                                                <div class="modal-content">
                                                    <div class="modal-header ">
                                                        <h5 class="modal-title mb-2" id="exampleModalLongTitle" style="font-size: 1.25rem;">
                                                            Submit Information
                                                        </h5> 
                                                    </div>
                                                    <form method="POST" id="add_fee_submit" action="{{ route('store_add_fee', ['customer_id' => $existing_ticket->Customer->id, 'ticket_id' => $ticket_id]) }}">
                                                        @csrf
                                                        <div class="modal-body overflow-y-scroll h-96">
                                                            <div class="m-2">
                                                                <div class="form-group text-left">
                                                                    <label for="amount" class="mb-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white"><strong>Add Fee:</strong></label>
                                                                    <div class="input-group mb-4">
                                                                        <div class="input-group-prepend d-flex">
                                                                            <span class="input-group-text peso">₱</span>
                                                                        </div>
                                                                        <input type="number" min="1" step="any" name="amount" id="amount" class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" aria-label="Amount (to the nearest peso)">
                                                                    </div>
                                                                    <label for="fee_data_details" class="mb-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white"><strong>What's the fee for?:</strong></label>
                                                                    <textarea class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="fee_data_details" id="fee_data_details" rows="3"></textarea>
                                                                    <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" />
                                                                    <input type="hidden" name="customer_first_name" value="{{ $existing_ticket->Customer->first_name }}" />
                                                                    <input type="hidden" name="customer_id" value="{{ $existing_ticket->Customer->id }}" />
                                                                    <input type="hidden" name="email_type" value="emailFee" />
                                                                </div>
                                                            </div> 

                                                            @forelse ($additonal_fees as $additonal_fee)
                                                                <div class="m-2 text-left" style="border-top: 1px solid #80808054; padding: 10px;">
                                                                    <div class="d-flex">
                                                                        <strong>Additonal Fee#{{ $loop->iteration }}:</strong> 
                                                                        <p class="mx-2">₱ {{ $additonal_fee->amount }}</p>
                                                                    </div> 
                                                                    <div class="block">
                                                                        <p class="mx-2"> - {{ $additonal_fee->fee_data_details }}</p>
                                                                    </div>

                                                                </div>
                                                            @empty
                                                                No Additional Fee added yet
                                                            @endforelse

                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="total_additional_fee_container d-flex p-1 items-center">
                                                                <h3 style="margin-right:5px;">TOTAL:</h3>
                                                                <p class="bold">₱{{ $additonal_fees->sum('amount') }}</p>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Add Fee</button>
                                                            <button type="button" class="btn btn-secondary" @click="open_add_fee = false">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> 


                                        <button @click="open_add_fee = !open_add_fee" type="button" class="btn btn-warning mx-1" data-toggle="modal" data-target="#view_payments">
                                            <span class="mdi mdi-cash"></span>  
                                
                                                @if ($additonal_fees)
                                                    <span class="badge badge-danger" style="font-size:10px">
                                                        {{ $additonal_fees->sum('amount') }}
                                                    </span>
                                                @endif
                                    
                                        </button>
                                        
                                    </div> 
                                    {{-- END - ADD FEE MODAL --}} 


                                </td>
                                <td class="px-6 py-4">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#view_logs">
                                        <span class="mdi mdi-book-check"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="customer-info d-flex w-full">
                        <div class="fcol w-1/2">
                            <p class="mb-2" style="font-size:12px">
                                STATUS:  

                                @if ($existing_ticket && $existing_ticket->status)
                                    @switch($existing_ticket->status)
                                        @case('forReview')
                                            <span class="badge text-white bg-gray-700" style="font-size:12px">
                                                For Review
                                            </span> 
                                            @break
                                    
                                        @case('pendingPayment')
                                            <span class="badge text-white bg-red-700" style="font-size:12px">
                                                Pending Payment
                                            </span> 
                                            @break
                                            
                                        @case('initialPaymentPaid')
                                            <span class="badge text-gray bg-yellow-500" style="font-size:12px">
                                                Initial Payment Paid
                                            </span> 
                                            @break

                                        @case('addingMedia')
                                            <span class="badge text-white bg-blue-700" style="font-size:12px">
                                                Adding Media/Images
                                            </span> 
                                            @break 

                                        @default
                                            {{-- Handle default case if needed --}}
                                    @endswitch 
                                @endif
                            </p>

                            <p class="mb-2" style="font-size:12px">SHIPPING METHOD:  
                                <span class="badge text-white bg-red-700" style="font-size:12px">
                                    {{ $existing_ticket->shipping_method == 'sea-cargo' ? 'Sea Cargo' : 'Air Cargo' }}
                                </span> 
                            </p>
                            <p class="mb-2" style="font-size:12px">
                                CURRENT STEP: <span class="badge text-white bg-green-700" style="font-size:12px" id="current_step"> {{ $steps }} </span>
                            </p>

                            @if ($notes->count())
                                <p class="mb-2" style="font-size:12px">
                                    NOTE: <span class="badge text-white bg-yellow-500" style="font-size:12px"> ADDED ({{ $notes->count() }})</span>
                                </p>
                            @else
                                <p class="mb-2" style="font-size:12px">
                                    NOTE: <span class="badge text-white bg-gray-900" style="font-size:12px"> NONE </span>
                                </p>
                            @endif 
                            
                            <p class="mb-2" style="font-size:12px">
                                FEES: 
                                @if ($additonal_fees && $additonal_fees->sum('amount') > 0 )
                                    <span class="badge text-white bg-purple-700" style="font-size:12px">ADDITONAL FEES ADDED</span>
                                @else
                                    <span class="badge text-white bg-gray-900" style="font-size:12px"> NO FEES ADDED </span>
                                @endif
                                
                            </p>
                        </div>
                        <div class="scol w-1/2">
                            <div class="customer-address">
                                <p class="mb-2 text-md" >
                                    Contact: <span class="text-white" > {{ $customerAddress->contact }} </span> 
                                </p>
                                <p class="mb-2 text-md" >
                                    Birthdate: <span class="text-white" > {{ date('F d, Y', strtotime($customerAddress->birthdate)) }} </span> 
                                </p>
                                <p class="mb-2 text-md" >
                                    Address: 
                                    <span class="text-white" > 
                                        {{ $customerAddress->street }}, {{ $customerAddress->barangay }}, {{ $customerAddress->city }} 
                                    </span> 
                                </p>
                                <p class="mb-2 text-md" >
                                    Region: <span class="text-white" > {{ $customerAddress->region }} </span> 
                                </p>
                                <p class="mb-2 text-md" >
                                    Provice: <span class="text-white" > {{ $customerAddress->province }} </span> 
                                </p>
                                <p class="mb-2 text-md" >
                                    Zipcode: <span class="text-white" > {{ $customerAddress->zipcode }} </span> 
                                </p>
                            </div>
                        </div>
                    </div>
 

                </div>
            </div>
    
        </div>

        <div class="col-md-5 mx-1">
            <div class="card">
                <div class="card-header py-1 flex items-center justify-between text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <span class="text-md">List of Products</span>    
                    <div x-data="{ open_add_product: false }">
                        <button type="button" class="btn btn-warning my-1 mx-2" style="font-size:14px;"  @click="open_add_product = !open_add_product">
                            Add Product
                        </button>
                    
                        {{-- START - ADD PRODUCT MODAL --}}
                        <div x-show="open_add_product" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-90"
                            x-bind:class="{ 'block': open_add_product, 'hidden': !open_add_product }" 
                            class="fixed inset-0 flex items-center justify-center z-50 hidden" style="z-index:9999;">
                    
                            <!-- Background overlay -->
                            <div class="absolute inset-0 bg-black opacity-20" @click="open_add_product = false"></div>

                            <!-- Modal Add Fee form -->
                            <div class="bg-gray-50 border-white border-solid border text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-b-lg rounded shadow-lg p-8 m-4 w-2/4 max-h-full text-center z-50 ">
                                <div class="modal-content">
                                    <div class="modal-header ">
                                        <h5 class="modal-title mb-2" id="exampleModalLongTitle" style="font-size: 1.25rem;">
                                            Submit Product Information
                                        </h5> 
                                    </div>
                                    <form method="POST" id="add_product_form" action="{{ route('addProducts', ['customer_id' => $existing_ticket->Customer->id]) }}">
                                        @csrf
                                        <div class="modal-body text-left block w-full">
                                            <div class="form-group w-full inline-block ">
                                                <label for="product_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name : </label>
                                                <input type="text" class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="product_name" id="add_product_name" required> 
                        
                                            </div>
                                            <div class="form-group w-full inline-block ">
                                                <label for="product_link" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Link : </label>
                                                <input type="url" class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="product_link" id="add_link" required placeholder="https://">
                        
                                            </div>
                                            <div class="form-group inline-block " style="width:49.5%">
                                                <label for="product_qty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quanity : </label>
                                                <input type="number" class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="product_qty" id="add_quantity" required/>
                                            </div>
                                            <div class="form-group inline-block " style="width:49.5%">
                                                <label for="product_variant" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Size / Color : </label>
                                                <input type="text" class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="product_variant" id="add_variation"/>
                                            </div>
                                            <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" /> 
                                            <input type="hidden" name="shipping_method" value="{{ $existing_ticket->shipping_method }}" />
                                            <input type="hidden" name="request_method" value="{{ $request_method }}" />
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Add Product</button>
                                            <button type="button" class="btn btn-secondary" @click="open_add_product = false">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- END - ADD PRODUCT MODAL --}}
                    </div> 
                
                </div>
                <div class="card-body p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-b-lg w-full">
            
                        <table class="table ticket_table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Variation</th>
                                <th scope="col">Link</th>
                                <th scope="col">Delete</th>
                                </tr>
                            </thead>

                                {{-- Access the related declared products --}}
                                @forelse ($products as $product)

                                    <tr class="border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->product_qty }}</td>
                                        <td>{{ $product->product_variant }}</td>
                                        <td>
                                            <a href="{{ $product->product_link }}" class="text-blue-600 no-underline bg-transparent" target="_blank" rel="noopener noreferrer">
                                                <span class="mdi mdi-link"></span> 
                                                Link
                                            </a>
                                        </td>
                                        <td>
                                            <form method="POST" id="deleteForm_{{ $product->id }}" action="{{ route('deleteProducts', ['product_id', $product->id]) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm deleteButton" data-id="{{ $product->id }}">
                                                    <span class="mdi mdi-delete-empty"></span>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                
                                @empty
                                    No data
                                @endforelse 

                        </table>
                    </div>
            </div>
        </div>  
        {{-- END - ASSIGN TICKET PAGE CONTENT --}} 

    </div>
    
    
    <div class="container flex flex-row max-w-full">
        {{-- START - TIMELINE --}}
        @include('tickets.timeline', 
                ['ticket_id' => $ticket_id,
                'customer_id' => $customer_id,
                'customer_fname' => $existing_ticket->Customer->first_name,
                'firstTicket' => $firstTicket,
                'notes' => $existing_ticket->ticketNotes,
                'steps' => $existing_ticket->steps,
                'additonal_fees' => $existing_ticket->ticketAdditionalFees,
                'existing_ticket' => $existing_ticket,
                'products' => $products,
                'status' => $existing_ticket->status,
                'request_method' => $request_method, 
                'admin_settings' => $admin_settings,
                'ticketPayments' => $existing_ticket->ticketPayments ]);
        {{-- END - TIMELINE --}}
    </div>
@endsection

@section('scripts')
    @parent


    <script> 

        //modal setup
        function modal() {
            return {
                open_add_note: false,
                open2: false
            }
        }
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2500
            }).then(() => {
                @php
                    session()->forget('success');
                @endphp
            });
        });
    </script>
    @endif
    


{{-- DELETE PRODUCT SWAL --}}
<script>

    document.querySelectorAll('.deleteButton').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm_' + id).submit();
                }
            })
        });
    });

</script>

{{-- LOADING SPINNER WHEN SENDING FORM REQUEST --}}

<script>
    // Intercept form submission for add_fee_submit form
    document.getElementById('add_fee_submit').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Show SweetAlert loading spinner
        Swal.fire({
            title: 'Please Wait...',
            allowEscapeKey: false,
            allowOutsideClick: false, 
            showConfirmButton: false,
            onOpen: ()=>{
                Swal.showLoading();
            }
        });

        // Submit the form after a short delay (to show the spinner)
        setTimeout(function() {
            document.getElementById('add_fee_submit').submit();
        }, 500); // Adjust delay time as needed
    });

    // Intercept form submission for note_submit form
    document.getElementById('note_submit').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Show SweetAlert loading spinner
        Swal.fire({
            title: 'Please Wait...',
            allowEscapeKey: false,
            allowOutsideClick: false, 
            showConfirmButton: false,
            onOpen: ()=>{
                Swal.showLoading();
            }
        });

        // Submit the form after a short delay (to show the spinner)
        setTimeout(function() {
            document.getElementById('note_submit').submit();
        }, 500); // Adjust delay time as needed
    });
</script>



{{-- TIMELINE TABS JS --}}
<script>
 
    const tabs = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    const currentStep = {{ $steps }}; // Assuming this is being rendered correctly from your backend

    tabs.forEach(tab => {   

         
        if(currentStep == tab.getAttribute('data-step')){
            // Add the active class to the matching tab
            tab.classList.add('active');
            tab.style.boxShadow = '0px 0px 10px 6px #15803d';
            
            // Show the content corresponding to the matching tab
            const contentId = tab.id.replace('_tab', '_content');
            document.getElementById(contentId).classList.remove('hidden');
        }
     

        tab.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove specified classes from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add the active class to the clicked tab
            tab.classList.add('active');

            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));

            // Show the content corresponding to the clicked tab
            const contentId = tab.id.replace('_tab', '_content');
            document.getElementById(contentId).classList.remove('hidden');
        });
    });


</script>

@endsection
