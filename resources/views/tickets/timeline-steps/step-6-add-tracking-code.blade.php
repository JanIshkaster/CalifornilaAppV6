<fieldset id="step-6" role="tabpanel" aria-labelledby="steps" class="body" aria-hidden="true">  
    <form method="POST" id="addTrackingCodeForm" enctype="multipart/form-data" action="{{ route('addTrackingCode', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}">
    @csrf
 
        <div id="alert-additional-content-2" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <div class="fieldset-content" id="add_tracking_code_form">   
                
                @if ($ticketTrackingCode && $ticketTrackingCode->first() && $ticketTrackingCode->first()->ticket_id == $ticket_id)

                    <div class="requested_data_container">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4 mb-4">
                            <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Tracking Code: <span class="text-lg lowercase font-bold text-yellow-300">{{ $ticketTrackingCode->first()->tracking_code }}</span> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 text-white font-bold">
                                        <a href="{{ $ticketTrackingCode->first()->tracking_link }}" target="_blank" class="text-blue-500 d-flex item-center" style="line-height:1.9;">
                                            <span class="mdi mdi-link text-xl px-2 py-0"></span> Click here to open tracking link
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
    
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                <p class="font-bold">Tracking Code Added!</p> 
                                </div>
                            </div>
                        </div>

                        @if ($steps == '6')
                            <a onclick="return confirmProceed();" href="{{ route('step_7', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}"  
                                class="mt-4 text-gray bg-yellow-800 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-large rounded-lg text-md px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
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
                                            window.location.href = '{{ route('step_7', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}';
                                        }
                                    });
                            
                                    // Prevent the default link behavior
                                    return false;
                                }
                            </script>  
                        @endif
    
    
                    </div>
    
                @else
    
                    <div class="form-group w-full mt-3 mb-3">
                        <label for="tracking_code" class="form-label block mb-2 text-md font-medium text-gray-900 dark:text-white">Tracking Code</label> 
                        <div class="flex">
                            <input type="text" value="" name="tracking_code" class="tracking_code rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                    <div class="form-group w-full mt-3 mb-3">
                        <label for="tracking_link" class="form-label block mb-2 text-md font-medium text-gray-900 dark:text-white">Tracking Link</label> 
                        <div class="flex">
                            <input type="url" value="" placeholder="https://" name="tracking_link" class="tracking_link rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                    <input type="hidden" value="{{ $steps }}" name="steps" class="steps"> 
                    <input type="hidden" value="{{ $ticket_id }}" name="ticket_id" class="ticket_id"> 
                    <input type="hidden" value="{{ $customer_id }}" name="customer_id" class="customer_id"> 
                    <input type="hidden" value="{{ $customer_fname }}" name="customer_fname" class="customer_fname">  
                    <input type="hidden" value="{{ $status }}" name="status" class="status">   
                    <input type="hidden" name="email_type" value="trackingCode" />
    
                    <button type="submit" class="btn btn-primary addTrackingCode w-full m-0"  disabled>Submit Tracking</button> 
    
                    <div class="border-t-4 px-4 py-3 shadow-md mt-4" role="alert">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                            </svg>
                            <span class="sr-only">Info</span>
                            <h3 class="text-lg font-medium">No Tracking Code yet!</h3>
                        </div>
                        <div class="mt-2 mb-4 text-sm">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.
                        </div>
                    </div>
    
                @endif
    
            </div>  
        </div> 

    </form>
</fieldset>

<script>
    // SHIPPING PAYMENT REQUEST
    document.addEventListener('DOMContentLoaded', function() {
        const trackingValue = document.querySelectorAll('#add_tracking_code_form .tracking_link'); // GET INPUT FIELDS
        const addTrackingCodeBtn = document.querySelectorAll('.addTrackingCode'); // GET SUBMIT BUTTONS

        // CHECK IF ELEMENTS EXIST
        if (trackingValue.length && addTrackingCodeBtn.length) {
            // ITERATE OVER EACH INPUT FIELD
            trackingValue.forEach(function(convertedProductValue) {
                // ADD EVENT LISTENER TO EACH INPUT FIELD
                convertedProductValue.addEventListener('change', function() {
                    // ITERATE OVER EACH BUTTON
                    addTrackingCodeBtn.forEach(function(addTrackingCodeBtn) {
                        // REMOVE DISABLED ATTRIBUTE FROM EACH BUTTON
                        addTrackingCodeBtn.removeAttribute('disabled');
                        declared_value();
                    });
                });
            });
        } 

    });


</script>
 
