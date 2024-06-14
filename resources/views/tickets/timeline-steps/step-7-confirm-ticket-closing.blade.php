<fieldset id="step-7" role="tabpanel" aria-labelledby="steps-uid-0-h-7" class="body" aria-hidden="true"> 
    <form method="POST" id="declaredProductEstimateForm" enctype="multipart/form-data"
            action="{{ route('initialPayment', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}">
        @csrf
    
        <p class="desc mb-4">Mark this package a pending to home</p>
        <h1 class="mt-4 font-bold text-xl">Product Information</h1>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-2">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                <th scope="col" class="px-6 py-3">Product</th>
                <th scope="col" class="px-6 py-3">Qty</th>
                <th scope="col" class="px-6 py-3">Variation</th>
                <th scope="col" class="px-6 py-3">Link</th> 
                </tr>
            </thead>

            {{-- Access the related declared products --}}
            @forelse ($products as $product)

                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $product->product_name }}</td>
                    <td class="px-6 py-4">{{ $product->product_qty }}</td>
                    <td class="px-6 py-4">{{ $product->product_variant }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ $product->product_link }}" class="text-blue-600 no-underline bg-transparent" target="_blank" rel="noopener noreferrer">
                            <span class="mdi mdi-link"></span> 
                            Link
                        </a>
                    </td> 
                </tr>
            
            @empty
                No data
            @endforelse 

        </table>  

        <div class="form-group-ticket-closing d-flex items-center my-3">
            <input type="checkbox" name="confirm_closing_ticket" id="confirm_closing_ticket" class="text-xs p-0 mx-2" required>
            <label class="form-check-label text-white" for="confirm_closing_ticket">After submit, customer will receive notification that their package is pending to their home.</label>
        </div>
        <button type="submit" id="submit_button" class="btn btn-primary confirmClosingTicket w-full m-0" disabled>Confirm</button>
        

    </form>

</fieldset>

<script>
    // CONFIRM CLOSING TICKET REQUEST
    document.getElementById('confirm_closing_ticket').addEventListener('click', function() {
        var submitButton = document.getElementById('submit_button');
        if (this.checked) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    });


</script>
 