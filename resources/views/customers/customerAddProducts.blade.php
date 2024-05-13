@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">
        <form method="POST" action="{{ route('customerAddProductsStore', ['id' => $customer_id]) }}"
            class="form-horizontal form-material mx-2" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-title p-3 mb-0 pb-0">
                            <small><i class="mdi me-2 mdi-dropbox"></i>Product details </small>
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
                                        <option value="" {{ old('warehouse_status') == '' ? 'selected' : '' }}>
                                        </option>
                                        <option value="US Warehouse">US Warehouse</option>
                                        <option value="PH Warehouse">PH Warehouse</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order-number" class="col-md-12">Order Number</label>
                                        <div class="col-md-12">
                                            <input required type="text" placeholder="" value="{{ old('order_number') }}"
                                                class="form-control ps-0 form-control-line" name="order_number"
                                                id="order_number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="merchant" class="col-md-12">Merchant</label>
                                        <div class="col-md-12">
                                            <input required type="text" value="{{ old('merchant') }}"
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
                                                <option value="" {{ old('package_type') == '' ? 'selected' : '' }}>
                                                </option>
                                                <option value="Normal">Normal</option>
                                                <option value="Electronics">Electronics</option>
                                                <option value="Fragile items">Fragile Items</option>
                                                <option value="Automotiveparts">Automotive Parts</option>
                                                <option value="Irregular-Sized Packages">Irregular-Sized Packages</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label for="quantity" class="col-md-12">Quantity</label>
                                        <div class="col-md-12">
                                            <input type="number" value="{{ old('quantity') }}" placeholder=""
                                                class="form-control ps-0 form-control-line" name="quantity" id="quantity">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="value" class="col-md-12">Value ($)</label>
                                        <div class="col-md-12">
                                            <input type="number" step=".01" value="{{ old('value') }}" placeholder=""
                                                class="form-control ps-0 form-control-line" name="value" id="value">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description" class="col-md-12">Description </label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="" value="{{ old('description') }}" p=""
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
                                                <option value="" selected=""> </option>
                                                <option value="Missing Values">Missing Values</option>
                                                <option value="Arrive on Warehouse">Arrive on Warehouse</option>
                                                <option value="Ready for Shipment">Ready for Shipment</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12 mb-0">Note</label>
                                        <div class="col-sm-12 border-bottom">
                                            <textarea name="note" class="form-select shadow-none ps-0 border-0 form-control-line">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 d-flex">
                                    <button class="btn btn-success mx-auto mx-md-0 text-white">
                                        Save Product</button>
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
                                <input type="file" id="uploadFile" name="product_image" class="img-fluid" onchange="preview(event)" >
                                <hr>
                                <img id="image_preview"/>
                                
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

        // Add image preview 
        function preview(event) {
            var image_preview = document.getElementById('image_preview');
            image_preview.src = URL.createObjectURL(event.target.files[0]);
            console.log(image_preview.src);
        }


    </script>
@endsection
