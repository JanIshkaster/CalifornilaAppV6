<fieldset id="step-1" role="tabpanel" aria-labelledby="steps-uid-0-h-1" class="body" aria-hidden="true"> 
    <form method="POST" id="request_declared_value" class="signup-form" action="#" novalidate="novalidate"> 
    
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
          
        @switch($request_method)
            @case('REQUEST_ESTIMATES')
                    <div class="fieldset-content">  
                        <div class="form-group w-full mt-3 mb-3">
                            <label for="find_bank" class="form-label">Product Value</label> 
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    $
                                </span>
                                <input type="number" step="0.01" value="" id="website-admin" name="dollar_conversion" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </div>
                        <input type="hidden" value="step_one" name="steps" class="steps">
                        <input type="hidden" value="" name="ini_customer_id" class="ini_customer_id">
                        <input type="hidden" value="" name="metafield_id" class="metafield_id">
                        <input type="hidden" value="" name="ticket_id" class="ticket_id">
                        <input type="hidden" value="" name="request_type" class="request_type">
                        
                        <div class="declared_data">
                            
                        </div>
                        <input type="submit" value="Submit" class="btn btn-primary declared_value_btn w-full m-0"  disabled/> 
                        <div class="spinner-border initial_btn" style="display:none" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>

                    </div>
                @break
        
            @case('DECLARE_SHIPMENTS')
                {{-- Your DECLARE_SHIPMENTS code here --}}
                @break
        
            @default
                {{-- Your default code here --}}
        @endswitch
    

    </form>

    <p class="mb-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
</fieldset>
