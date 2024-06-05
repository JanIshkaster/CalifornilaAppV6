@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full" id="calculatorPage">

        <div class="row w-full">
                <div class="row">

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-title p-3 mb-0 pb-0">
                                <p>
                                    <i class="mdi me-2 mdi-dropbox"></i>
                                    Mentric Information
                                </p>

                                <hr />
                            </div>

                            <div class="card-body cal">
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
                                            <label for="quantity" class="col-md-12">Product Name:</label>
                                            <div class="col-md-12">
                                                <input type="text" value="" placeholder=""
                                                    class="form_description form-control ps-0 form-control-line"
                                                    name="product-name" id="product-name">
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
                                    <div class="w-full block text-center mb-3 mt-3">
                                        <div class="form-group w-1/4 p-1 inline-block m-0">
                                            <button class="get_estimate btn btn-success w-full" disabled>
                                            <span class="mdi mdi-calculator"></span>
                                               GET ESTIMATE
                                            </button>
                                            <!-- <input type="submit" class="calculate_data btn btn-primary" value="Calculate" style=""> -->
                                        </div>
                                        <div class="form-group w-1/4 p-1 inline-block m-0">
                                            <button class="clear_estimate btn btn-warning text-white w-full" disabled>
                                            <span class="mdi mdi-close"></span> 
                                               CLEAR
                                            </button> 
                                        </div>
                                        <div class="form-group w-1/4 p-1 inline-block m-0">
                                            <button class="clear_estimate btn btn-primary text-white w-full" id="printButton" disabled>
                                            <span class="mdi mdi-printer"></span>
                                                Print
                                            </button> 
                                        </div>
                                    </div>


                                </div>

                                 



                            </div>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-title p-3 mb-0 pb-0">
                                <p class="d-flex items-center mb-3">
                                    <i class="mdi me-2 mdi-dropbox"></i> 
                                    <small class="m-0">Calculator Playground</small> </span>
                                </p>
                                <hr />
                            </div>
                            <div class="card-body">
                                <div class="output-calculator"> </div>
                            </div>
                        </div>
                    </div> 

                </div> 
        </div>

    </div>
@endsection

@section('scripts')
    @parent

    {{-- SWEET ALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CUSTOM JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {  

            //CALCULATE JS
            var calculateData = document.querySelectorAll('.get_estimate');
            calculateData.forEach(function(element) {
                element.addEventListener('click', function() {

                    // Show loading spinner effect
                    Swal.fire({
                        title: 'Calculating...', 
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    }); 

                    var pesoValue = parseFloat(document.querySelector('#value').value);
                    var customsTax = 0;

                    if(pesoValue >= 10001){
                        customsTax = pesoValue * 0.1;
                    } 
 
                    var productName = document.querySelector('#product-name').value
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
                        productName: productName,
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
                    ldata = " <h3>  Product Name :  <strong>"+data.productName+"</strong>   </h3> ";
                    ldata += "<hr style='width:100%' class='mx-auto my-3'>  ";
                    ldata +=  " <p>  Lbs :  "+data.weight+ "     </p> ";
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

                    // Simulate a delay (you can replace this with your actual calculation logic)
                    setTimeout(() => {
                        // Hide loading spinner
                        Swal.close();

                    // CALCULATOR OUTPUT
                    document.querySelector('.output-calculator').innerHTML = ldata;

                    // diable buttons checker
                    if (outputCalculator.innerHTML.trim() !== '') {
                        printButton.removeAttribute('disabled'); // Enable the print button if output is displayed
                        clearButton.removeAttribute('disabled'); // Enable the clear button if output is displayed
                        getEstimateButton.setAttribute('disabled', 'true'); 
                    }

                    }, 500); // Simulated delay of 1 second 




                    
                });
            });

            // Set clear button
            const clearButton = document.querySelector('.clear_estimate'); //GET CLEAR BUTTON
            const outputCalculator = document.querySelector('.output-calculator'); //GET OUTPUT
            const printButton = document.getElementById('printButton'); // GET BUTTON
            const inputFields = document.querySelectorAll('#calculatorPage input'); //GET INPUT FIELDS
            const getEstimateButton = document.querySelector('.get_estimate'); // GET ESTIMATE BUTTON

            // Clear fields and output if clear button is clicked
            clearButton.addEventListener('click', () => {
                // Clear the content of the output calculator
                outputCalculator.innerHTML = '';

                // Clear the input field
                inputFields.forEach(input => {
                    input.value = ''; 
                });

                //Disable the print and clear button
                clearButton.setAttribute('disabled', 'true'); 
                printButton.setAttribute('disabled', 'true'); 
            });

            printButton.addEventListener('click', () => {
                // Call the print function
                printContent(outputCalculator);
            });

            function printContent(element) {
                const printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write('<html><head><title>Print</title></head><body>');
                printWindow.document.write(element.innerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }

            //Enable the get estimate button if input fields are filled
            inputFields.forEach(input => {
                input.addEventListener('change', function() {
                    getEstimateButton.removeAttribute('disabled');
                });
            });



        });
    </script>
@endsection
