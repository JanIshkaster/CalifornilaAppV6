@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">
        <form method="POST"
            action="{{ route('customerUpdateProduct', ['id' => $customer_id, 'product_id' => $customer_product_id]) }}"
            class="form-horizontal form-material mx-2" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-title p-3 mb-0 pb-0">
                            <small><i class="mdi me-2 mdi-dropbox"></i>Edit Product details </small>
                            <hr>
                        </div>
                        <div class="card-body">

                            {{-- <input type="hidden" value="product_a" name="type" id="type"> --}}
                            <input type="hidden" name="customer_id" value="{{ $customer_id }}">

                            <div class="form-group">
                                <label class="col-md-12 mb-0">Warehouse status</label>
                                <div class="col-sm-12 border-bottom">
                                    <select name="warehouse_status"
                                        class="form-select shadow-none ps-0 border-0 form-control-line">
                                        <option value=""
                                            {{ old('warehouse_status', $customerProduct->warehouse_status) == '' ? 'selected' : '' }}>
                                        </option>
                                        <option value="US Warehouse"
                                            {{ old('warehouse_status', $customerProduct->warehouse_status) == 'US Warehouse' ? 'selected' : '' }}>
                                            US Warehouse</option>
                                        <option value="PH Warehouse"
                                            {{ old('warehouse_status', $customerProduct->warehouse_status) == 'PH Warehouse' ? 'selected' : '' }}>
                                            PH Warehouse</option>
                                    </select>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order-number" class="col-md-12">Order Number</label>
                                        <div class="col-md-12">
                                            <input required type="text" placeholder=""
                                                value="{{ old('order_number', $customerProduct->order_number) }}"
                                                class="form-control ps-0 form-control-line" name="order_number"
                                                id="order_number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="merchant" class="col-md-12">Merchant</label>
                                        <div class="col-md-12">
                                            <input required type="text"
                                                value="{{ old('merchant', $customerProduct->merchant) }}"
                                                placeholder="Ex: Amazon" class="form-control ps-0 form-control-line"
                                                name="merchant" id="merchant">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="package_type" class="col-md-12">Package type</label>
                                        <div class="col-md-12 border-bottom">
                                            <select name="package_type"
                                                class="form-select shadow-none ps-0 border-0 form-control-line">
                                                <option value=""
                                                    {{ old('package_type', $customerProduct->package_type) == '' ? 'selected' : '' }}>
                                                </option>
                                                <option value="Normal"
                                                    {{ old('package_type', $customerProduct->package_type) == 'Normal' ? 'selected' : '' }}>
                                                    Normal</option>
                                                <option value="Electronics"
                                                    {{ old('package_type', $customerProduct->package_type) == 'Electronics' ? 'selected' : '' }}>
                                                    Electronics</option>
                                                <option value="Fragile items"
                                                    {{ old('package_type', $customerProduct->package_type) == 'Fragile items' ? 'selected' : '' }}>
                                                    Fragile Items</option>
                                                <option value="Automotiveparts"
                                                    {{ old('package_type', $customerProduct->package_type) == 'Automotiveparts' ? 'selected' : '' }}>
                                                    Automotive Parts</option>
                                                <option value="Irregular-Sized Packages"
                                                    {{ old('package_type', $customerProduct->package_type) == 'Irregular-Sized Packages' ? 'selected' : '' }}>
                                                    Irregular-Sized Packages</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label for="quantity" class="col-md-12">Quantity</label>
                                        <div class="col-md-12">
                                            <input type="number" value="{{ old('quantity', $customerProduct->quantity) }}"
                                                placeholder="" class="form-control ps-0 form-control-line" name="quantity"
                                                id="quantity">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="value" class="col-md-12">Value ($)</label>
                                        <div class="col-md-12">
                                            <input type="number" step=".01"
                                                value="{{ old('value', $customerProduct->value) }}" placeholder=""
                                                class="form-control ps-0 form-control-line" name="value" id="value">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description" class="col-md-12">Description </label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder=""
                                                value="{{ old('description', $customerProduct->description) }}" p=""
                                                class="form-control ps-0 form-control-line" name="description"
                                                id="description">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Status</label>
                                        <div class="col-sm-12 border-bottom">
                                            <select name="status"
                                                class="form-select shadow-none ps-0 border-0 form-control-line">
                                                <option value=""
                                                    {{ old('status', $customerProduct->status) == '' ? 'selected' : '' }}>
                                                </option>
                                                <option value="Missing Values"
                                                    {{ old('status', $customerProduct->status) == 'Missing Values' ? 'selected' : '' }}>
                                                    Missing Values</option>
                                                <option value="Arrive on Warehouse"
                                                    {{ old('status', $customerProduct->status) == 'Arrive on Warehouse' ? 'selected' : '' }}>
                                                    Arrive on Warehouse</option>
                                                <option value="Ready for Shipment"
                                                    {{ old('status', $customerProduct->status) == 'Ready for Shipment' ? 'selected' : '' }}>
                                                    Ready for Shipment</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Note</label>
                                        <div class="col-sm-12 border-bottom">
                                            <textarea name="note" class="form-select shadow-none ps-0 border-0 form-control-line">{{ old('note', $customerProduct->note) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 d-flex">
                                    <button class="btn btn-success mx-auto mx-md-0 text-white">
                                        Update Product</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-title p-3 mb-0">
                            <small><i class="mdi me-2 mdi-checkbox-multiple-blank"></i>Select Product Image </small>
                            <hr>
                        </div>
                        <div class="card-body pl-3 pr-3">
                            <div class="form-group">
                                <input type="file" id="uploadFile" name="product_image" class="img-fluid"
                                    multiple="">
                                <hr>
                                <div id="image_preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
