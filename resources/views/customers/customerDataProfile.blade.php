@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">

        <div class="row w-full">
            <div class="col-lg-12 col-xlg-12 col-md-12 mb-3">
                <div
                    class="card w-full block p-6  border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <div class="card-body profile-card">
                        <div class="row text-center">
                            <?php if(isset($customerDataProfile)) { ?>

                            <div class="col-6">
                                <h4
                                    class="card-title mt-2 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ ucfirst($customerDataProfile['first_name']) }}
                                    {{ ucfirst($customerDataProfile['last_name']) }}
                                </h4>
                                <h6 class="card-subtitle font-normal text-gray-700 dark:text-gray-400 py-2">
                                    <a href="mailto:{{ $customerDataProfile['email'] ?? '' }}">{{ $customerDataProfile['email'] ?? '' }}
                                    </a>
                                </h6>
                                <h6 class="card-subtitle font-normal text-gray-700 dark:text-gray-400 py-2">
                                    <a href="tel:{{ $customerDataProfile['phone'] ?? '' }}">{{ $customerDataProfile['phone'] ?? '' }}
                                    </a>
                                </h6>

                                <div class="col-12 mt-2">
                                    @if (is_array($customerDataProfile['addresses']) && count($customerDataProfile['addresses']) > 0)
                                        @php
                                            $row = $customerDataProfile['addresses'][0];
                                        @endphp
                                        <div class="card">
                                            <div class="card-body">
                                                <p class="card-text font-normal text-gray-700 dark:text-gray-400">Address
                                                    :{{ $row['address1'] }}
                                                    {{ $row['address2'] }}
                                                    {{ $row['city'] }}
                                                    {{ $row['province'] }}
                                                    {{ $row['country'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>


                            <div class="col-6 text-center justify-content-center mt-3">
                                <div class="col-12">
                                    <a href="{{ route('customerAddProducts', ['id' => $customerDataProfile['id']]) }}"
                                        class="btn btn-success mx-auto mx-md-0 text-black m-2">
                                        <h5> <i class="mdi me-2 mdi-note-plus"> </i> Add Products </h5>
                                    </a>
                                    <a href="#" class="btn btn-warning mx-auto mx-md-0 text-black m-2">

                                        <h5> <i class="mdi me-2 mdi-eye"> </i> View All Products </h5>
                                    </a>
                                    <a href="{{ route('customerConsolidate', ['id' => $customerDataProfile['id']]) }}"
                                        class="btn btn-info mx-auto mx-md-0 text-black m-2">

                                        <h5> <i class="mdi me-2 mdi-codepen"> </i> Boxed the Products </h5>
                                    </a>

                                </div>

                            </div>


                            <?php } else  {?>
                            <h4 class="card-title mt-2">
                                Data not available
                            </h4>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xlg-12 col-md-12 mb-3">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="customer_view"
                                class="table mb-3 table-xs table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Images</th>
                                        <th class="border-top-0">Description</th>
                                        <th class="border-top-0">Merchant</th>
                                        <th class="border-top-0">Order # </th>
                                        <th class="border-top-0">Value </th>
                                        <th class="border-top-0">Qty </th>
                                        <th class="border-top-0">Warehouse </th>
                                        <th class="border-top-0">Package Type </th>
                                        <th class="border-top-0">status </th>
                                        <th class="border-top-0">Date & Time </th>
                                        <th class="border-top-0">Modify </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customerAddedProductData as $product)
                                        @if ($product['status'] != 'pending_payment')
                                            <tr>
                                                <td class="text-center;">
                                                    @if (!empty($product['product_image']))
                                                        @php
                                                            $product_image = explode(',', $product['product_image']);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $product_image[0]) }}"
                                                            height="70" width="70" style="margin: 0 auto;" />
                                                    @else
                                                        <span style="color:red;">'Missing Image'</span>
                                                    @endif

                                                </td>
                                                <td>{{ substr($product['description'], 0, 100) . '...' }}</td>
                                                <td>{{ $product['merchant'] }}</td>
                                                <td>{{ $product['order_number'] }}</td>
                                                <td>${{ $product['value'] }}</td>
                                                <td>{{ $product['quantity'] }}</td>
                                                <td>{{ $product['warehouse_status'] }}</td>
                                                <td>{{ $product['package_type'] }}</td>
                                                <td>{{ $product['status'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($product['created_at'])->format('m/d/Y') }}
                                                </td>
                                                <td>
                                                    @if ($product['warehouse_status'] == 'PH Warehouse' && $product['status'] == 'Arrive on Warehouse')
                                                        <i class="mdi me-2 mdi-check text-lime-500">Qualify to packed for shipment </i>
                                                        <a href="{{ url('customers/' . $customerDataProfile['id'] . '/edit_product' . '/' . $product['id']) }}"
                                                            class="btn btn-success mx-auto mx-md-0 text-white w-full"> Edit
                                                        </a>
                                                    @else
                                                        <a href="{{ url('customers/' . $customerDataProfile['id'] . '/edit_product' . '/' . $product['id']) }}"
                                                            class="btn btn-success mx-auto mx-md-0 text-white w-full"> Edit
                                                        </a>
                                                        <form method="POST"
                                                            action="{{ url('customers/' . $customerDataProfile['id'] . '/delete_product' . '/' . $product['id']) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger mx-auto mx-md-0 text-white w-full"
                                                                onclick="confirmDelete(event)">Delete</button>
                                                        </form>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
