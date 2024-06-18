<fieldset id="step-1" role="tabpanel" aria-labelledby="steps-uid-0-h-1" class="body" aria-hidden="true"> 
    <form method="POST" id="declaredProductEstimateForm" enctype="multipart/form-data"
            action="{{ route('initialPayment', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}">
        @csrf
    
        <p class="desc">Add value to the request or declared </p>

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

        @php
            $requestMethod = strtolower($request_method) //Shipping method 
        @endphp 

        @switch(true)
            @case(strpos($requestMethod, 'request_estimates') !== false)
                <div class="fieldset-content" id="initial_payment_form_container">   
                        
                    @if ($ticketPayments && $ticketPayments->first() && $ticketPayments->first()->ticket_id == $ticket_id)
                        <div class="form-group w-full mt-3 mb-3">
                            <label for="find_bank" class="form-label">Product Value</label> 
                        </div>
                        <div class="requested_data_container">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4 mb-4">
                                <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Handling Fee {{ $admin_settings->handling_fee }}%</th>
                                        <th scope="col" class="px-6 py-3">Customs Tax {{ $admin_settings->custom_tax }}%</th>
                                        <th scope="col" class="px-6 py-3">Convenience Fee {{ $admin_settings->convenience_fee }}%</th>
                                        <th scope="col" class="px-6 py-3">Credit card Fee {{ $admin_settings->credit_card_fee }}%</th>
                                        <th scope="col" class="px-6 py-3">Product Value</th>  
                                        <th scope="col" class="px-6 py-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_handling_fee ?? '' }}</td>
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_custom_tax ?? '' }}</td>
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_convenience_fee ?? '' }}</td>
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_credit_card_fee ?? '' }}</td>
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_product_value ?? '' }}</td>
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
                            <label for="find_bank" class="form-label block mb-2 text-md font-medium text-gray-900 dark:text-white">Product Value</label> 
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    $
                                </span>
                                <input type="number" step="0.01" value="" name="converted_product_value" class="converted_product_value rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </div>
                        <input type="hidden" value="{{ $steps }}" name="steps" class="steps"> 
                        <input type="hidden" value="{{ $ticket_id }}" name="ticket_id" class="ticket_id"> 
                        <input type="hidden" value="{{ $customer_id }}" name="customer_id" class="customer_id"> 
                        <input type="hidden" value="{{ $customer_fname }}" name="customer_fname" class="customer_fname"> 
                        <input type="hidden" value="{{ $request_method }}" name="request_type" class="request_type">
                        <input type="hidden" value="{{ $status }}" name="status" class="status">
                        
                        @php
                            $totalQuantity = collect(json_decode($products, true))->sum('product_qty');
                        @endphp 

                        <input type="hidden" value="{{ $totalQuantity ?? '' }}" name="product_qty" class="product_qty">
                        <input type="hidden" value="{{ $admin_settings->handling_fee ?? '' }}" name="handling_fee" class="handling_fee">
                        <input type="hidden" value="{{ $admin_settings->custom_tax ?? '' }}" name="custom_tax" class="custom_tax">
                        <input type="hidden" value="{{ $admin_settings->convenience_fee ?? '' }}" name="convenience_fee" class="convenience_fee">
                        <input type="hidden" value="{{ $admin_settings->credit_card_fee ?? '' }}" name="credit_card_fee" class="credit_card_fee">
                        <input type="hidden" value="{{ $admin_settings->dollar_conversion ?? '' }}" name="dollar_conversion" class="dollar_conversion">      
                        
                        {{-- CALCULATED PRODUCT --}}   
                        <input type="hidden" name="totalHandlingFee" class="totalHandlingFee" value="">
                        <input type="hidden" name="totalCustomTax" class="totalCustomTax" value="">
                        <input type="hidden" name="totalConvenienceFee" class="totalConvenienceFee" value="">
                        <input type="hidden" name="totalCreditCardFee" class="totalCreditCardFee" value=""> 
                        <input type="hidden" name="productValue" class="productValue" value=""> 
                        <input type="hidden" value="" name="productTotalValue" class="productTotalValue"> 
                        <input type="hidden" value="initial payment" name="payment_type" class="payment_type"> 
                        <input type="hidden" name="email_type" value="initialPayment" />

                        {{-- DISPLAY OUTPUT --}}
                        <div class="output_declared_data_initial_payment">
                            
                        </div>

                        <label class="block mb-2 text-md font-medium text-gray-900 dark:text-white" for="small_size">Attach Estimate Request Image/File</label>
                        <input class="block w-full mb-5 text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                        name="requestEstimateFile" id="small_size" type="file" accept="image/*"> 

                        <button type="submit" class="btn btn-primary declared_value_btn w-full m-0"  disabled>Submit</button> 

                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mt-4" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                <p class="font-bold">Send request for payment to customer via email</p>
                                <p class="text-sm">Automatically generate a product with price base on request in Shopify store for customers to pay</p>
                                </div>
                            </div>
                        </div>

                    @endif
 
                    </div>
                @break
        
            @case(strpos($requestMethod, 'declare_shipments') !== false)
                {{-- Your DECLARE_SHIPMENTS code here --}}
                <div class="fieldset-content" id="initial_payment_form_container">   
                        
                    @if ($ticketPayments && $ticketPayments->first() && $ticketPayments->first()->ticket_id == $ticket_id)
                        <div class="form-group w-full mt-3 mb-3">
                            <label for="find_bank" class="form-label">Product Value</label> 
                        </div>
                        <div class="requested_data_container">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4 mb-4">
                                <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Handling Fee {{ $admin_settings->handling_fee }}%</th>
                                        <th scope="col" class="px-6 py-3">Customs Tax {{ $admin_settings->custom_tax }}%</th> 
                                        <th scope="col" class="px-6 py-3">Product Value</th>  
                                        <th scope="col" class="px-6 py-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_handling_fee ?? '' }}</td>
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_custom_tax ?? '' }}</td> 
                                        <td class="px-6 py-4">₱ {{$ticketPayments->first()->total_product_value ?? '' }}</td>
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
                            <label for="find_bank" class="form-label block mb-2 text-md font-medium text-gray-900 dark:text-white">Product Value</label> 
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    $
                                </span>
                                <input type="number" step="0.01" value="" name="converted_product_value" class="converted_product_value rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </div>
                        <input type="hidden" value="{{ $steps }}" name="steps" class="steps"> 
                        <input type="hidden" value="{{ $ticket_id }}" name="ticket_id" class="ticket_id"> 
                        <input type="hidden" value="{{ $customer_id }}" name="customer_id" class="customer_id"> 
                        <input type="hidden" value="{{ $customer_fname }}" name="customer_fname" class="customer_fname"> 
                        <input type="hidden" value="{{ $request_method }}" name="request_type" class="request_type">
                        <input type="hidden" value="{{ $status }}" name="status" class="status">

                        {{-- ADMIN SETTINGS -> FEE --}}
                        <input type="hidden" value="{{ $totalQuantity ?? '' }}" name="product_qty" class="product_qty">
                        <input type="hidden" value="{{ $admin_settings->handling_fee ?? '' }}" name="handling_fee" class="handling_fee">
                        <input type="hidden" value="{{ $admin_settings->custom_tax ?? '' }}" name="custom_tax" class="custom_tax">
                        <input type="hidden" value="{{ $admin_settings->convenience_fee ?? '' }}" name="convenience_fee" class="convenience_fee">
                        <input type="hidden" value="{{ $admin_settings->credit_card_fee ?? '' }}" name="credit_card_fee" class="credit_card_fee">
                        <input type="hidden" value="{{ $admin_settings->dollar_conversion ?? '' }}" name="dollar_conversion" class="dollar_conversion">      
                        
                        {{-- CALCULATED PRODUCT --}}   
                        <input type="hidden" name="totalHandlingFee" class="totalHandlingFee" value="">
                        <input type="hidden" name="totalCustomTax" class="totalCustomTax" value="">
                        <input type="hidden" name="totalConvenienceFee" class="totalConvenienceFee" value="">
                        <input type="hidden" name="totalCreditCardFee" class="totalCreditCardFee" value=""> 
                        <input type="hidden" name="productValue" class="productValue" value=""> 
                        <input type="hidden" value="" name="productTotalValue" class="productTotalValue"> 
                        <input type="hidden" value="initial payment" name="payment_type" class="payment_type"> 
                        <input type="hidden" name="email_type" value="initialPayment" />

                        {{-- DISPLAY OUTPUT --}}
                        <div class="output_declared_data_initial_payment">
                            
                        </div>

                        <label class="block mb-2 text-md font-medium text-gray-900 dark:text-white" for="small_size">Attach Estimate Request Image/File</label>
                        <input class="block w-full mb-5 text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                        name="requestEstimateFile" id="small_size" type="file" accept="image/*"> 

                        <button type="submit" class="btn btn-primary declared_value_btn w-full m-0"  disabled>Submit</button> 

                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mt-4" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                <p class="font-bold">Send request for payment to customer via email</p>
                                <p class="text-sm">Automatically generate a product with price base on request in Shopify store for customers to pay</p>
                                </div>
                            </div>
                        </div>

                    @endif
 
                    </div>
                @break
        
            @default
                {{-- Your default code here --}}
        @endswitch 

    </form>

</fieldset>

<script>
    // INITIAL PAYMENT - COMPUTATION
    
    document.addEventListener('DOMContentLoaded', function() {
        const convertedProductValues = document.querySelectorAll('#initial_payment_form_container .converted_product_value'); // GET INPUT FIELDS
        const declaredValueBtns = document.querySelectorAll('.declared_value_btn'); // GET SUBMIT BUTTONS

        // CHECK IF ELEMENTS EXIST
        if (convertedProductValues.length && declaredValueBtns.length) {
            // ITERATE OVER EACH INPUT FIELD
            convertedProductValues.forEach(function(convertedProductValue) {
                // ADD EVENT LISTENER TO EACH INPUT FIELD
                convertedProductValue.addEventListener('change', function() {
                    // ITERATE OVER EACH BUTTON
                    declaredValueBtns.forEach(function(declaredValueBtn) {
                        // REMOVE DISABLED ATTRIBUTE FROM EACH BUTTON
                        declaredValueBtn.removeAttribute('disabled');
                        declared_value();
                    });
                });
            });
        }

        // Generate table
        function declared_value() {
            const productValue = parseFloat(document.querySelector('.converted_product_value').value * document.querySelector('.dollar_conversion').value);
            const productQty = parseFloat(document.querySelector('.product_qty').value);
            const dollar_conversion = parseFloat(document.querySelector('.dollar_conversion').value);
            const totalHandlingFee = ((productValue / 100) * parseFloat(document.querySelector('.handling_fee').value));
            const totalCustomTax = ((productValue / 100) * parseFloat(document.querySelector('.custom_tax').value));

            let totalConvenienceFee = ((productValue / 100) * parseFloat(document.querySelector('.convenience_fee').value));
            let totalCreditCardFee = ((productValue / 100) * parseFloat(document.querySelector('.credit_card_fee').value));
            
            // Use PHP to conditionally modify the JavaScript variables
            <?php if(strpos($requestMethod, 'declare_shipments') !== false): ?>
                totalConvenienceFee = 0;
                totalCreditCardFee = 0;
            <?php endif; ?>

            const productTotalValue = (productQty * productValue) + totalHandlingFee + totalCustomTax + totalConvenienceFee + totalCreditCardFee;

            // PUT VALUES IN THE HIDDEN INPUT
            document.querySelector('.totalHandlingFee').value = totalHandlingFee; 
            document.querySelector('.totalCustomTax').value = totalCustomTax; 
            document.querySelector('.totalConvenienceFee').value = totalConvenienceFee; 
            document.querySelector('.totalCreditCardFee').value = totalCreditCardFee; 
            document.querySelector('.productValue').value = productValue; 
            document.querySelector('.productTotalValue').value = productTotalValue; 



            var data = '<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4 mb-4">';
                data += '   <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
                data += '       <tr>';
                data += '           <th scope="col" class="px-6 py-3">Handling Fee ('+ document.querySelector('.handling_fee').value +'%)</th>';
                data += '           <th scope="col" class="px-6 py-3">Customs Tax ('+ document.querySelector('.custom_tax').value +'%)</th>';
                data += '           <th scope="col" class="px-6 py-3">Convenience Fee ('+ document.querySelector('.convenience_fee').value +'%)</th>';
                data += '           <th scope="col" class="px-6 py-3">Credit card Fee ('+ document.querySelector('.credit_card_fee').value +'%)</th>';
                data += '           <th scope="col" class="px-6 py-3">Product Value</th>';  
                data += '           <th scope="col" class="px-6 py-3">Total</th>';
                data += '       </tr>';
                data += '   </thead>';
                data += '   <tbody>';
                data += '       <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">';
                data += '           <td class="px-6 py-4">₱ ' + totalHandlingFee.toFixed(2) + '</td>';
                data += '           <td class="px-6 py-4">₱ ' + totalCustomTax.toFixed(2) + '</td>';
                data += '           <td class="px-6 py-4">₱ ' + totalConvenienceFee.toFixed(2) + '</td>';
                data += '           <td class="px-6 py-4">₱ ' + totalCreditCardFee.toFixed(2) + '</td>';
                data += '           <td class="px-6 py-4">₱ ' + productValue.toFixed(2) + '</td>';
                data += '           <td class="px-6 py-4 text-white font-bold">₱ ' + productTotalValue.toFixed(2) + '</td>';
                data += '       </tr>';
                data += '   </tbody>';
                data += '</table>';

            document.querySelector('.output_declared_data_initial_payment').innerHTML = data; 
        }


    });


</script>
