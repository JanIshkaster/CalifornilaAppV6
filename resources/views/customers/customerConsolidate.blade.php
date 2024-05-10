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
                                if(isset($customerProduct)){ 
                                        foreach($customerProduct as $product){  
                                        if($product->warehouse_status == "PH Warehouse" && $product->status == "Arrive on Warehouse" ) {
                                    ?>
                                    <div class="card col-md-3 p-0 mx-2 mt-2 product_list">

                                        <input type="checkbox" data-id="<?= $product['id'] ?>"
                                            data-price="<?= $product['value'] ?>" data-shipment="<?= $product['status'] ?>"
                                            class="product_data" />

                                        <div class="card-body">
                                            <p class="card-text py-1 m-0" style="font-size:18px; !important">
                                                <strong><?= $product['description'] ?></strong>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Quantity:</strong>
                                                    <?= $product['quantity'] ?>
                                                </small>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Merchant:</strong>
                                                    <?= $product['merchant'] ?>
                                                </small>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Order #:</strong>
                                                    <?= $product['order_number'] ?>
                                                </small>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Date:</strong> <?php $date = date_create($product['created_at']); ?>
                                                    <?= date_format($date, 'm/d/Y H:i') ?>
                                                </small>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Shipment status:</strong>
                                                    <?= $product['status'] ?>
                                                </small>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Package type:</strong>
                                                    <?= $product['package_type'] ?>
                                                </small>
                                            </p>
                                            <p class="p-0 m-0">
                                                <small class="p-0 m-0"><strong>Value (Peso):</strong>
                                                    <?= $product['value'] ?>
                                                </small>
                                            </p>
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
                                {{-- <form id="form_box_info" method="POST" class="form-horizontal form-material mx-2"> --}}

                                <input type="hidden" value="" class="list_ids" name="list_ids" />

                                <input type="hidden" value="" class="form_shipment_status" name="shipment_status" />

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
                                                    <option value="<?= $products['package_type'] ?? '' ?>" selected>
                                                        <?= $products['package_type'] ?? '' ?></option>
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
                                                    class="form-control ps-0 form-control-line auto_weight" name="weight"
                                                    id="weight">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label for="quantity" class="col-md-12">Lenght (INCH)</label>
                                            <div class="col-md-12">
                                                <input type="number" step=".01" value="" placeholder=""
                                                    class="form-control ps-0 form-control-line auto_lenght" name="lenght"
                                                    id="lenght">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label for="quantity" class="col-md-12">Width (INCH)</label>
                                            <div class="col-md-12">
                                                <input type="number" step=".01" value="" placeholder=""
                                                    class="form-control ps-0 form-control-line auto_width" name="width"
                                                    id="width">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label for="quantity" class="col-md-12">Height (INCH)</label>
                                            <div class="col-md-12">
                                                <input type="number" step=".01" value="" placeholder=""
                                                    class="form-control ps-0 form-control-line auto_height" name="height"
                                                    id="height">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <div class="form-group ">
                                            <a class="get_estimate btn btn-primary" value="Proceed on saving"
                                                style="display:none">
                                                Proceed on saving
                                            </a>
                                            <!-- <input type="submit" class="calculate_data btn btn-primary" value="Calculate" style=""> -->
                                        </div>
                                    </div>

                                </div>

                                {{-- </form> --}}



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
        document.addEventListener('DOMContentLoaded', function() {
            var productDataElements = document.querySelectorAll('.product_data');
            productDataElements.forEach(function(productData) {
                productData.addEventListener('change', function() {
                    var data = 0;
                    var value = 0;
                    var ids = "pkg-";
                    var shipment_status = "";
                    var listIds = '';
                    productDataElements.forEach(function(productData) {
                        if (productData.checked) {
                            var price = parseFloat(productData.getAttribute('data-price'));
                            ids += productData.getAttribute('data-id') + "-";
                            value = value + price;
                            data++;
                            shipment_status = productData.getAttribute('data-shipment')
                                .trim();
                            listIds += productData.getAttribute('data-id') + ",";
                            document.getElementById('value').value = value;
                        }
                    });
                    listIds = listIds.slice(0, -1);
                    document.querySelector('.list_ids').value = listIds;
                    document.querySelector('.form_shipment_status').value = shipment_status;
                    ids = ids.slice(0, -1);
                    document.querySelector('.form_description').value = ids;
                    document.querySelector('.selected_product').innerHTML = data;

                    if (data > 0) {
                        document.querySelector('.get_estimate').style.display = "block";
                    } else if (data == 0) {
                        document.querySelector('.get_estimate').style.display = "none";
                    }
                });
            });


            //CALCULATE JS
            var calculateData = document.querySelectorAll('.get_estimate');
            calculateData.forEach(function(element) {
                element.addEventListener('click', function() {

                    var pesoValue = parseFloat(document.querySelector('#value').value);
                    var customsTax = 0;

                    if(pesoValue >= 10001){
                        customsTax = pesoValue * 0.1;
                    } 
 

                    var air_multiplier = 480;
                    var sea_multiplier = 199;
                    var dollar_conversion = 57;
                    var weight = parseFloat(document.querySelector('.auto_weight').value);
                    var length = parseFloat(document.querySelector('.auto_lenght').value);
                    var width = parseFloat(document.querySelector('.auto_width').value);
                    var height = parseFloat(document.querySelector('.auto_height').value);
                    var type = ""; 
                    var dimensional_weight = ((length * width * height) / 166).toFixed(2);
                    var dimensional_weight_display = dimensional_weight+' = '+ length +' x '+ width +' x ' + height +' / 166';
                    var air_cargo = (weight * air_multiplier).toFixed(2); //7.99; 
                    var sea_cargo = (weight * sea_multiplier).toFixed(2); //2.75;
                    var air_cargo_dw = (dimensional_weight * air_multiplier).toFixed(2); //7.99;  
                    var sea_cargo_dw = (dimensional_weight * sea_multiplier).toFixed(2); //2.75;
                    var air_cargo_dw_display = air_cargo_dw+' = '+dimensional_weight+' x '+air_multiplier ;
                    var sea_cargo_dw_display = sea_cargo_dw+' = '+dimensional_weight+' x '+sea_multiplier ;
                    var handling_fee_air = (air_cargo * 0.1).toFixed(2);
                    var handling_fee_sea = (sea_cargo * 0.1).toFixed(2); 
                    var special_handling = 0;
                    var convenient_fee_air = 0;
                    var convenient_fee_sea = 0;
                    var local_shipping_fee = 0;
                    var subtotal_air = (parseFloat(air_cargo) + parseFloat(special_handling) + parseFloat(handling_fee_air) + parseFloat(customsTax)).toFixed(2);
                    var subtotal_sea = (parseFloat(sea_cargo) + parseFloat(special_handling) + parseFloat(handling_fee_sea) + parseFloat(customsTax)).toFixed(2);
 
                    if(weight > dimensional_weight){
                        var weight_txt = 'weight: ' + weight + ' GREATER than the dimensional weight: ' + dimensional_weight;
                    } else if(weight <= dimensional_weight){
                        var weight_txt = 'weight: ' + weight + ' LESS than or EQUAL the dimensional weight: ' + dimensional_weight;
                    }


                    var data = {
                        weight: weight,
                        length: length,
                        width: width,
                        height: height,
                        type: type, 
                        air_cargo: air_cargo,
                        sea_cargo: sea_cargo,
                        dimensional_weight: dimensional_weight,
                        dimensional_weight_display: dimensional_weight_display,
                        air_cargo_dw: air_cargo_dw,
                        sea_cargo_dw: sea_cargo_dw,
                        air_cargo_dw_display: air_cargo_dw_display,
                        sea_cargo_dw_display: sea_cargo_dw_display,
                        handling_fee_air: handling_fee_air,
                        handling_fee_sea: handling_fee_sea,
                        customsTax: customsTax,
                        special_handling: special_handling,
                        convenient_fee_air: convenient_fee_air,
                        convenient_fee_sea: convenient_fee_sea,
                        local_shipping_fee: local_shipping_fee,
                        weight_txt: weight_txt,
                        subtotal_air: subtotal_air,
                        subtotal_sea: subtotal_sea,
                        dollar_conversion: dollar_conversion
                    };

                    // var datas = JSON.parse(JSON.stringify(data)); 
                    ldata =  " <p>  Lbs :  "+data.weight+ "     </p> ";
                    ldata += " <p>  Dimentional weight :  <br />"+data.dimensional_weight_display+"   </p> ";
                    ldata += "<hr style='width:100%' class='mx-auto my-3'>  ";
                    ldata += "<p>Air Cargo ₱: "+data.air_cargo+"      </p> ";
                    ldata += "<p>Sea Cargo ₱: "+data.sea_cargo+"      </p> ";
                    ldata += "<p>  Air Cargo Dimention :  <br />"+data.air_cargo_dw_display+"      </p> ";
                    ldata += "<p>  Sea Cargo Dimention :  <br />"+data.sea_cargo_dw_display+"      </p>";
                    ldata += "<p>  Handling Fee Air(10%) ₱: "+data.handling_fee_air+"      </p>";
                    ldata += "<p>  Handling Fee Sea(10%) ₱:  "+data.handling_fee_sea+"      </p>";
                    ldata += "<p>  Customs Tax ₱:  "+data.customsTax+"      </p>";
                    ldata += "<p>  Special Handling ₱:  "+data.special_handling+"      </p>";
                    ldata += "<p>  Local shipping fee ₱: "+data.local_shipping_fee+"      </p>";
                    ldata += "<p>  Convenient fee air ₱: "+data.convenient_fee_air+"      </p>";
                    ldata += "<p>  Convenient fee sea ₱:  "+data.convenient_fee_sea+"     </p>";
                    ldata += "<p>  Dollar to Peso Convertion :  "+data.dollar_conversion+"     </p>";
                    ldata += "<hr style='width:100%' class='mx-auto my-3'>";
                    ldata += "<p><strong> "+data.weight_txt+" </strong> </p>";
                    ldata += "<hr style='width:100%' class='mx-auto my-3'>";
                    ldata += "<p><strong>Air Cargo Total ₱:</strong> "+data.subtotal_air+"      </p> ";
                    ldata += "<p><strong>Sea Cargo Total ₱:</strong> "+data.subtotal_sea+"      </p> ";
                    // Add the rest of your ldata concatenations here...
                    document.querySelector('.output-calculator').innerHTML = ldata;
                });
            });




        });
    </script>
@endsection
