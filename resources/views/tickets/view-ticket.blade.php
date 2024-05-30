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
                <div class="card-header">
                    Ticket ID: {{ $ticket_id }}
                    | 
                    <small>
                        <span class="mdi mdi-account-cog"></span>
                        Admin: {{ Auth::user()->name }}
                    </small>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Customer Information</h4>
                    <table class="table table-stripeds">
                        <thead>
                            <tr>
                                <th scope="col">Name </th>
                                <th scope="col">Email</th>
                                <th scope="col">Add/View Note</th>
                                <th scope="col">Additional Fees</th>
                                <th scope="col">Logs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <a href="#" target="blank">
                                    <i class="fas fa-link"></i>
                                    {{ $existing_ticket->Customer->first_name }} {{ $existing_ticket->Customer->last_name }}
                                </a>
                            </td>
                            <td>{{ $existing_ticket->Customer->email }}</td>
                            <td>
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
                                        <div class="bg-white rounded shadow-lg p-8 m-4 w-2/4 max-h-full text-center z-50">
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
                                                                <label for="noted_data" class="mb-2"><strong>Add Note:</strong></label>
                                                                <textarea class="form-control" name="ticket_note" id="noted_data" rows="3"></textarea>
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
                            <td class="block"> 
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
                                        <div class="bg-white rounded shadow-lg p-8 m-4 w-2/4 max-h-full text-center z-50">
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
                                                                <label for="amount" class="mb-2"><strong>Add Fee:</strong></label>
                                                                <div class="input-group mb-4">
                                                                    <div class="input-group-prepend d-flex">
                                                                        <span class="input-group-text peso">₱</span>
                                                                    </div>
                                                                    <input type="number" min="1" step="any" name="amount" id="amount" class="form-control" aria-label="Amount (to the nearest peso)">
                                                                </div>
                                                                <label for="fee_data_details" class="mb-2"><strong>What's the fee for?:</strong></label>
                                                                <textarea class="form-control" name="fee_data_details" id="fee_data_details" rows="3"></textarea>
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
                                                        <div class="total_additional_fee_container">
                                                            <h3>TOTAL:</h3>
                                                            <p>{{ $additonal_fees->sum('amount') }}</p>
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
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#view_logs">
                                    <span class="mdi mdi-book-check"></span>
                                </button>
                            </td>
                        </tbody>
                    </table>

                    <p class="mb-2" style="font-size:12px">
                        STATUS: <span class="badge text-white bg-blue-700" style="font-size:12px"> STATUS </span> 
                    </p>

                    <p class="mb-2" style="font-size:12px">SHIPPING METHOD: 
                        
                        <span class="badge text-white bg-red-700" style="font-size:12px">
                            {{ $existing_ticket->shipping_method == 'sea-cargo' ? 'Sea Cargo' : 'Air Cargo' }}
                        </span>

                    </p>
                    <p class="mb-2" style="font-size:12px">
                        CURRENT STEP: <span class="badge text-white bg-green-700" style="font-size:12px"> STEP </span>
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
                        TOTAL FEES: 
                        @if ($additonal_fees && $additonal_fees->sum('amount') > 0 )
                            <span class="badge text-white bg-purple-700" style="font-size:12px">ADDITONAL FEES ADDED</span>
                        @else
                            <span class="badge text-white bg-gray-900" style="font-size:12px"> NO FEES ADDED </span>
                        @endif
                        
                    </p>
               



                </div>
            </div>
    
        </div>

        <div class="col-md-5 mx-1">
            <div class="card">
                <div class="card-header flex items-center">
                    List of Products |     
                        <div x-data="{ open_add_product: false }">
                            <button type="button" class="btn btn-warning m-2" style="font-size:14px;"  @click="open_add_product = !open_add_product">
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
                                <div class="bg-white rounded shadow-lg p-8 m-4 w-2/4 max-h-full text-center z-50">
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
                                                    <label for="noted_data">Product Name : </label>
                                                    <input type="text" class="form-control" name="product_name" id="add_product_name" required> 
                            
                                                </div>
                                                <div class="form-group w-full inline-block ">
                                                    <label for="noted_data">Link : </label>
                                                    <input type="url" class="form-control" name="product_link" id="add_link" required placeholder="https://">
                            
                                                </div>
                                                <div class="form-group inline-block " style="width:49.5%">
                                                    <label for="noted_data">Quanity : </label>
                                                    <input type="number" class="form-control" name="product_qty" id="add_quantity" required/>
                                                </div>
                                                <div class="form-group inline-block " style="width:49.5%">
                                                    <label for="noted_data">Size / Color : </label>
                                                    <input type="text" class="form-control" name="product_variant" id="add_variation"/>
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
                <div class="card-body">
            
                        <table class="table table-striped">
                            <thead>
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

                                    <tr>
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
                'firstTicket' => $firstTicket,
                'notes' => $existing_ticket->ticketNotes,
                'steps' => $existing_ticket->steps,
                'additonal_fees' => $existing_ticket->ticketAdditionalFees,
                'existing_ticket' => $existing_ticket,
                'products' => $products,
                'request_method' => $request_method, 
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

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove specified classes from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add the active classes to the clicked tab
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
