<fieldset id="step-5" role="tabpanel" aria-labelledby="steps" class="body" aria-hidden="true">  

    @php 
        $jsonData = null;
        $isPaid = false; 

        if ($WebHookPaymentData->isNotEmpty()) {
            foreach ($WebHookPaymentData as $paymentData) {
                if ($paymentData->payment_type == 'shipping-payment') {
                    $jsonData = json_decode($paymentData->data, true);
                    $order_status_url = $jsonData['order_status_url'] ?? '';
                    $shopify_product_id = $jsonData['line_items'][0]['product_id'] ?? '';

                    // Assuming $ticketShippingPayments is fetched similarly to $WebHookPaymentData
                    $ticketShippingPayment = $ticketShippingPayments->where('shopify_product_sp_id', $shopify_product_id)->first();
                    if ($ticketShippingPayment) {
                        $isPaid = true;
                        break; // Exit the loop once a match is found
                    }
                }
            }
        }
    @endphp

 

    @if ($jsonData && $isPaid)  

        <div id="alert-additional-content-3" class="p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <div class="flex items-center">
            <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <h3 class="text-lg font-medium">Shipping Payment Request Paid!</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
            More info about this info success goes here. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.
            </div>
            <div class="flex">
            <a href="{{ $order_status_url ?? '' }}" target="_blank" class="text-white bg-green-800 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-green-300 
            font-large rounded-lg text-md px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                Confirm order link
            </a>

            @if ($steps == '5')
                <a onclick="return confirmProceed();" href="{{ route('step_6', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}"  
                    class="text-gray bg-yellow-800 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-large rounded-lg text-md px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
                    <span class="mdi mdi-page-next" style="margin-right:5px;"></span>
                    Proceed to next step?
                </a>
                {{-- SWAL FOR PROCEED BUTTON --}}
                <script>
                    function confirmProceed() {
                        Swal.fire({
                            title: 'Proceed to next step?',
                            text: 'Are you sure you want to proceed?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // User clicked "Yes," proceed with the link
                                window.location.href = '{{ route('step_6', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}';
                            }
                        });
                
                        // Prevent the default link behavior
                        return false;
                    }
                </script>  
            @endif

            </div>
        </div>
         
    @else
        <div id="alert-additional-content-2" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <div class="flex items-center">
            <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <h3 class="text-lg font-medium">Initial Payment not yet Paid!</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
            More info about this info danger goes here. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.
            </div>
            <div class="flex">
            </div>
        </div>
    @endif

    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
</fieldset>
 
