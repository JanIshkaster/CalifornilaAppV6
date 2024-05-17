@extends('layouts.default')

@section('content')

    <div class="page-title-container max-w-full text-center mb-3">
        <h1 class="text-2xl font-medium">Declared Products</h1>
    </div>

    <div class="container flex flex-row max-w-full">


        {{-- START - TICKET PAGE CONTENT --}}

        <div class="container-fluid w-full py-3 px-0">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Customer ID</th>
                            <th scope="col" class="px-6 py-3">Customer Name</th>
                            <th scope="col" class="px-6 py-3">Customer Email</th>
                            <th scope="col" class="px-6 py-3">Product Name</th> 
                            <th scope="col" class="px-6 py-3">Date and Time</th> 
                            <th scope="col" class="px-6 py-3">Modify</th> 
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($customers as $customer)
                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">#{{ $customer->id ?? '' }}</td>
                            <td class="px-6 py-4">{{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}</td> 
                            <td class="px-6 py-4">{{ $customer->email ?? '' }}</td>
                            <td class="px-6 py-4">
                                <ul>
                                @foreach ($customer->buyingAssistanceProducts as $product)
                                    <li>
                                        <a href="{{ $product->product_link }}" target="blank" class="d-block px-2 py-1 hover:bg-red-50 hover:text-white hover:bg-gray-900">
                                            <i class="mdi mdi-open-in-new"></i>
                                            {{ $product->product_name ?? '' }}  
                                        </a>                                        
                                    </li>
                                @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4">
                                @if ($customer->buyingAssistanceProducts->isNotEmpty())
                                    {{ $customer->buyingAssistanceProducts->sortByDesc('created_at')->first()->created_at->format('m/d/Y | H:i') }}
                                @else
                                    <h3>No data available</h3>
                                @endif
                            </td>   
                            <td class="px-6 py-4"> 
                                <a class="btn btn-primary" href="">
                                    <i class="mdi mdi-eye-circle-outline" aria-hidden="true"></i>
                                </a>
                    
                                <a class="btn btn-info" href="">
                                    <i class="mdi me-2 mdi-calculator" aria-hidden="true"></i>
                                </a>
                    
                                <a class="btn btn-warning" href="">
                                    <i class="mdi me-2 mdi-note" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="odd">
                            <td valign="top" colspan="6" class="dataTables_empty">No data available in table</td>
                        </tr>
                    @endforelse                                                             

                    </tbody>
                </table>
                
            </div> 
        </div>

        {{-- END - TICKET PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
