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
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </a>
                                </td>
                                <td>{{ $customer->email }}</td>
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
                                            class="fixed inset-0 flex items-center justify-center z-50">
                                    
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
                                                    <form method="POST" id="note_submit" action="{{ route('store_ticket_note', ['customer_id' => $customer->id]) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="m-2">
                                                                <div class="form-group text-left">
                                                                    <label for="noted_data" class="mb-2">Add Note:</label>
                                                                    <textarea class="form-control" name="ticket_note" id="noted_data" rows="3"></textarea>
                                                                    <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" />
                                                                    <input type="hidden" name="METAFIELD_ID" value="" />
                                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}" />
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
                                <td class="d-flex">
                                    <button type="button" class="btn btn-primary mx-1" data-toggle="modal" data-target="#add_fees">
                                        <span class="mdi mdi-plus-box"></span>
                                    </button>
                                    <button type="button" class="btn btn-warning mx-1" data-toggle="modal" data-target="#view_payments">
                                        <span class="mdi mdi-cash"></span>

                                        @foreach ($customer->Ticket as $ticket)
                                            @if ($ticket->ticketAdditionalFees->first() && $ticket->ticketAdditionalFees->first()->amount > 0)
                                                <span class="badge badge-danger" style="font-size:10px">
                                                    {{ $ticket->ticketAdditionalFees->first()->amount }}
                                                </span>
                                            @endif
                                       @endforeach
                                    
                                    </button>
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
                        <p class="mb-2" style="font-size:12px">
                            SHIPPING METHOD: <span class="badge text-white bg-red-700" style="font-size:12px"> SHIPPING_METHOD </span> 
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
                            TOTAL FEES: <span class="badge text-white bg-purple-700" style="font-size:12px"> TOTAL FEES </span>
                        </p>

                    </div>
                </div>
     
            </div>

            <div class="col-md-5 mx-1">
                <div class="card">
                    <div class="card-header flex items-center">
                        List of Products |     
                        <button type="button" class="btn btn-warning m-2" data-toggle="modal" data-target="#add_product" style="font-size:10px;">
                            Add Product 
                        </button> 
                    
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
                                    @forelse ($customer->DeclaredProducts as $product)

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
                                                <a class="btn btn-danger" href="">
                                                    <span class="mdi mdi-delete-empty"></span>
                                                    Delete
                                                </a>
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
@endsection

@section('scripts')
    @parent

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

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
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2500
        })
    </script>
    @endif

@endsection
