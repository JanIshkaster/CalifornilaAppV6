<fieldset id="step-1" role="tabpanel" aria-labelledby="steps-uid-0-h-1" class="body" aria-hidden="true"> 
    <form method="POST" id="request_declared_value" class="signup-form" action="#" novalidate="novalidate">

        @php
            // Split the string into an array using the comma as the delimiter
            $request_method_array = explode(',', $request_method);
        
            // Now you have $request_method_array as an array
            // You can use it as needed, for example, to remove duplicates
            $unique_request_method = array_unique($request_method_array);
        
            // Now you can implode the unique array if needed
            $r_method = implode(',', $unique_request_method);
        @endphp
        
        <h2 class="text-white mb-2 font-bold">
            {{ ucfirst(strtolower($r_method)) == 'Request_estimates' ? 'Request Estimate' : 'Declared Estimate' }} value
        </h2>
    
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




    </form>
</fieldset>
