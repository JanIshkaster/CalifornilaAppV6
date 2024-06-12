<fieldset id="step-4" role="tabpanel" aria-labelledby="steps-uid-0-h-4" class="body" aria-hidden="true"> 

    <form method="POST" id="shippingPaymentForm" enctype="multipart/form-data"
            action="{{ route('shippingPayment', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}">
        @csrf
    
        <p class="desc">Add the Final Payment Total</p>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4">
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

        <div class="fieldset-content" id="shipping_payment_form_container">   
                
            @if ($ticketShippingPayments && $ticketShippingPayments->first() && $ticketShippingPayments->first()->ticket_id == $ticket_id)

                <div class="form-group w-full mt-3 mb-3">
                    <label for="find_bank" class="form-label">Shipping Value</label> 
                </div>
                <div class="requested_data_container">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4 mb-4">
                        <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Shipping Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 text-white font-bold">₱ {{$ticketPayments->first()->total_product_price ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                            <div>
                            <p class="font-bold">Request sent!</p> 
                            </div>
                        </div>
                    </div>


                </div>

            @else

                <div class="form-group w-full mt-3 mb-3">
                    <label for="find_bank" class="form-label block mb-2 text-md font-medium text-gray-900 dark:text-white">Shipping Value</label> 
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            ₱
                        </span>
                        <input type="number" step="0.01" value="" name="shipping_value" class="shipping_value rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>
                <input type="hidden" value="{{ $steps }}" name="steps" class="steps"> 
                <input type="hidden" value="{{ $ticket_id }}" name="ticket_id" class="ticket_id"> 
                <input type="hidden" value="{{ $customer_id }}" name="customer_id" class="customer_id"> 
                <input type="hidden" value="{{ $customer_fname }}" name="customer_fname" class="customer_fname"> 
                <input type="hidden" value="{{ $request_method }}" name="request_type" class="request_type">
                <input type="hidden" value="{{ $status }}" name="status" class="status"> 
                <input type="hidden" value="shipping payment" name="payment_type" class="payment_type"> 

                <label class="block mb-2 text-md font-medium text-gray-900 dark:text-white" for="small_size">Attach Estimate Shipping Request Image/File</label>
                <input class="block w-full mb-5 text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                name="requestShippingEstimateFile" id="small_size" type="file" accept="image/*"> 

                <button type="submit" class="btn btn-primary reqShippingEstimateBtn w-full m-0"  disabled>Submit</button> 

                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mt-4" role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                        <div>
                        <p class="font-bold">Send request for shipping payment to customer via email</p>
                        <p class="text-sm">Automatically generate a product with price base on request in Shopify store for customers to pay</p>
                        </div>
                    </div>
                </div>

            @endif

        </div>
       
         
    </form>

</fieldset>


<script>
    // SHIPPING PAYMENT REQUEST
    document.addEventListener('DOMContentLoaded', function() {
        const shippingValue = document.querySelectorAll('#shipping_payment_form_container .shipping_value'); // GET INPUT FIELDS
        const reqShippingEstimateBtns = document.querySelectorAll('.reqShippingEstimateBtn'); // GET SUBMIT BUTTONS

        // CHECK IF ELEMENTS EXIST
        if (shippingValue.length && reqShippingEstimateBtns.length) {
            // ITERATE OVER EACH INPUT FIELD
            shippingValue.forEach(function(convertedProductValue) {
                // ADD EVENT LISTENER TO EACH INPUT FIELD
                convertedProductValue.addEventListener('change', function() {
                    // ITERATE OVER EACH BUTTON
                    reqShippingEstimateBtns.forEach(function(reqShippingEstimateBtn) {
                        // REMOVE DISABLED ATTRIBUTE FROM EACH BUTTON
                        reqShippingEstimateBtn.removeAttribute('disabled');
                        declared_value();
                    });
                });
            });
        } 

    });


</script>

 