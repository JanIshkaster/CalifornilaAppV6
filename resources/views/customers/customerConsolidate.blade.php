@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full" id="customerConsolidate">

        <div class="row w-full">
            <form name="" method="POST" class="form-horizontal form-material mx-2" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-title p-3 mb-0 pb-0">
                                <small><i class="mdi me-2 mdi-dropbox"></i>Choose product you want to consolidate on a box.
                                </small>
                                <hr />
                            </div>
                            <div class="card-body">
                                <section class=" row">
                                    <small class="mb-2"> Click on the box if you want to select the package </small>

                                    <?php 
                                if(isset($product_data)){
                                        foreach($product_data as $products){ 
                                        if($products['warehouse'] == "PH Warehouse" && $products['status'] == "Arrive on Warehouse" ) {?>
                                    <div class="card col-md-3 p-0  product_list">
                                        <input type="checkbox" data-id="<?= $products['id'] ?>"
                                            data-price="<?= $products['value'] ?>"
                                            data-shipment="<?= $products['shipment_status'] ?> " class="product_data" />
                                        <div class="card-body">
                                            <p class="card-text p-1 m-0" style="font-size:12px; !important">
                                                <?= $products['description'] ?></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">Quantity: <?= $products['quantity'] ?>
                                                </small></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">Merchant: <?= $products['vendor'] ?>
                                                </small></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">Order #:
                                                    <?= $products['order_number'] ?> </small></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">D: <?php $date = date_create($products['date_time']); ?>
                                                    <?= date_format($date, 'Y/m/d H:i:s') ?> </small></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">Shipment:
                                                    <?= $products['shipment_status'] ?> </small></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">Package type:
                                                    <?= $products['product_type'] ?> </small></p>
                                            <p class="p-0 m-0"><small class="p-0 m-0">Value (Peso):
                                                    <?= $products['value'] ?> </small></p>
                                        </div>
                                        <div class="card-footer text-center">
                                            <!-- <a class="p-3" href="#"> View product details</a> -->
                                        </div>
                                    </div>

                                    <?php 
                                            } 
                                        }
                                    }
                                ?>

                                </section>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-title p-3 mb-0 pb-0">
                                <p><i class="mdi me-2 mdi-dropbox"></i> <small>Calculator Playground v.1</small> </span></p>
                                <hr />
                            </div>
                            <div class="card-body">
                                <div class="output-calculator"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-title p-3 mb-0 pb-0">
                                <p><i class="mdi me-2 mdi-dropbox"></i>Mentric Information | <span
                                        style="font-size:13px">Number of product selected: <span
                                            class="selected_product"></span> </span></p>

                                <hr />
                            </div>

                            <div class="card-body">
                                <form id="form_box_info" method="POST" class="form-horizontal form-material mx-2">
                                    <input type="hidden" value="" class="list_ids" name="list_ids" />

                                    <input type="hidden" value="" class="form_shipment_status"
                                        name="shipment_status" />
                                    <div class="row p-3">
                                        <?php if(isset($validation)) :  ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validation->listErrors() ?>
                                        </div>
                                        <?php endif ?>

                                        <div class="col-md-12">
                                            <div class="form-group ">
                                                <label for="quantity" class="col-md-12">Description:</label>
                                                <div class="col-md-12">
                                                    <input type="text" value="" placeholder=""
                                                        class="form_description form-control ps-0 form-control-line"
                                                        name="description" id="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="package-type" class="col-md-12">Package type</label>
                                                <div class="col-md-12 border-bottom">
                                                    <select id="package_type" name="package-type"
                                                        class="form-select shadow-none ps-0 border-0 form-control-line">
                                                        <option value=""></option>
                                                        <option value="Normal">Normal</option>
                                                        <option value="Electronics">Electronics</option>
                                                        <option value="Fragile items">Fragile Items</option>
                                                        <option value="Automotiveparts">Automotive Parts</option>
                                                        <option value="Irregular-Sized Packages">Irregular-Sized Packages
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="value" class="col-md-12">Total value of the product
                                                    (Peso)</label>
                                                <div class="col-md-12">
                                                    <input type="number" step=".01" value="" placeholder=""
                                                        class="form-control ps-0 form-control-line" name="value"
                                                        id="value">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label for="quantity" class="col-md-12">Weight (LBS)</label>
                                                <div class="col-md-12">
                                                    <input type="number" step=".01" value="" placeholder=""
                                                        class="form-control ps-0 form-control-line auto_weight"
                                                        name="weight" id="weight">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label for="quantity" class="col-md-12">Lenght (INCH)</label>
                                                <div class="col-md-12">
                                                    <input type="number" step=".01" value="" placeholder=""
                                                        class="form-control ps-0 form-control-line auto_lenght"
                                                        name="lenght" id="lenght">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label for="quantity" class="col-md-12">Width (INCH)</label>
                                                <div class="col-md-12">
                                                    <input type="number" step=".01" value="" placeholder=""
                                                        class="form-control ps-0 form-control-line auto_width"
                                                        name="width" id="width">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label for="quantity" class="col-md-12">Height (INCH)</label>
                                                <div class="col-md-12">
                                                    <input type="number" step=".01" value="" placeholder=""
                                                        class="form-control ps-0 form-control-line auto_height"
                                                        name="height" id="height">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-3">
                                            <div class="form-group ">
                                                <input type="submit" class="get_estimate btn btn-primary"
                                                    value="Proceed on saving" style="display:none">
                                                <!-- <input type="submit" class="calculate_data btn btn-primary" value="Calculate" style=""> -->
                                            </div>
                                        </div>

                                    </div>

                                </form>



                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
