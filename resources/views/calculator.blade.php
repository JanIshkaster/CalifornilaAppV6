@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">


        {{-- START - CALCULATOR PAGE CONTENT --}}
        <div class="container-fluid max-w-full p-5">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <form method="POST" action={{ route('calculator') }}>
                            @csrf

                            <input type="hidden" name="sessionToken" class="session-token">
                            <input hidden name="host" value="{{ \Illuminate\Support\Facades\Request::get('host') }}">

                            <fieldset>

                                <!-- Form Name -->
                                <legend>Calculator Playground</legend>

                                <hr style="width:100%">
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="weight">Weight</label>
                                    <div class="col-md-12">
                                        <input id="weight" name="weight" type="text" placeholder=""
                                            value="{{ old('weight') }}" class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="lenght">Lenght</label>
                                    <div class="col-md-12">
                                        <input id="lenght" name="lenght" type="text" placeholder=""
                                            value="{{ old('lenght') }}" class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="width">Width</label>
                                    <div class="col-md-12">
                                        <input id="width" name="width" type="text" value="{{ old('width') }}"
                                            placeholder="" class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="height">Height</label>
                                    <div class="col-md-12">
                                        <input id="height" name="height" type="text" value="{{ old('height') }}"
                                            placeholder="" class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="value">Package Value ($) </label>
                                    <div class="col-md-12">
                                        <input id="value" name="value" type="text" value="{{ old('value') }}"
                                            placeholder="" class="form-control input-md">

                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="type">Package type</label>
                                    <div class="col-md-12">
                                        <select id="type" name="type" class="form-control">
                                            <option value="{{ old('type') }}"> {{ old('type') }} </option>
                                            <option value="Normal">Normal</option>
                                            <option value="Electronics">Electronics</option>
                                            <option value="Fragile Item">Fragile Item</option>
                                            <option value="Automotive Parts">Automotive Parts</option>
                                            <option value="Irregular-Sized Packages">Irregular-Sized Packages</option>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="col-md-12">
                                <button type="submit" class="btn get_estimate p-2 col-8 border btn-shipping"
                                    style="width:100%">Get
                                    Estimate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <?php if(isset($total)){?>
                            <p> Lbs : <?= $total['lbs'] ?> </p>
                            <p> Dimentional weight : <?= $total['dimensional_weight'] ?> </p>
                            <hr style="width:100%">

                            <p> Air Cargo Dimention : <?= $total['air_cargo_dw'] ?> </p>
                            <p> Sea Cargo Dimention : <?= $total['sea_cargo_dw'] ?> </p>
                            <hr />
                            <p> Air Cargo : P<?= $total['air_cargo_converted'] ?> </p>
                            <p> Sea Cargo : P<?= $total['sea_cargo_converted'] ?> </p>

                            <p> Handling Fee Air(10%) : P<?= $total['handling_fee_air'] ?> </p>
                            <p> Handling Fee Sea(10%) : P<?= $total['handling_fee_sea'] ?> </p>
                            <p> Customs Tax : <?= $total['custom_tax'] ?> </p>
                            <p> Local shipping fee : P<?= $total['local_shipping_fee'] ?> </p>
                            <p> Paymango fee air (0.035%): P<?= $total['convenient_fee_air'] ?> </p>
                            <p> Paymango fee sea (0.035%): P<?= $total['convenient_fee_sea'] ?> </p>
                            <p> Dollar to Peso Convertion: P<?= $total['dollar-convertion'] ?> </p>
                            <hr style="width:100%">
                            <!-- <p><strong><?= strtoupper($total['special_handling']) ?></strong></p>
                                        <hr style="width:100%"> -->
                            <p><strong> <?= strtoupper($total['weight_txt']) ?></strong> </p>
                            <hr style="width:100%">
                            <p><strong> Sea Cargo : <?= $total['sea-cargo'] ?> </strong> </p>
                            <p><strong> Air Cargo : <?= $total['air-cargo'] ?> </strong> </p>

                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END - CALCULATOR PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
