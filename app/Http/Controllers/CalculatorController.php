<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    //CALCULATOR SETUP 

    private $weight;
    private $lenght;
    private $width;
    private $height;
    private $value;
    private $type;
    private $product_value; 
    private $dashboardData;

    public function calculatorView(Request $request)
    {  
        $host = $request->input('host');
        return view('calculator', ['host' => $host]); 
    }

    public function calculator(Request $request)
    {  
        $data =  [];
        if($request->isMethod('post')){
            $rules = [
                'weight' => 'required',
                'lenght' => 'required',
                'width' => 'required',
                'height' => 'required',
                'type' => 'required'
            ];  
    
            $validator = Validator::make($request->all(), $rules);
    
            if($validator->fails()){
                $data['validation'] = $validator->errors();
            }else{
                $this->lenght = $request->input('lenght');
                $this->weight = $request->input('weight');
                $this->width = $request->input('width');
                $this->height = $request->input('height');
                $this->type = $request->input('type');
                $this->product_value = $request->input('value');
                $data_val = $this->product_calculate();
                $data['total'] = $data_val;
            }
        }
    
        $data['title'] = 'Calculator';
        $data['subtitle'] = 'Test';
        $data['lefnotification'] =  $this->dashboardData;
     
        return redirect()->tokenRoute('calculator', $data);
    }
    


    //CALCULATIONS
    public function product_calculate(){ 
     
        $subtotal_air = 0;
        $subtotal_sea = 0; 
        $subtotal = 0;
        $lbs = 0;
      
        $initial_value = $this->product_value;

        $product_values = $this->product_value * 1.1025;
        $this->product_value = $this->product_value * 1.1025; 

        $data['product_value_text'] =  $this->product_value."=".$initial_value."* 1.1025";
   
        $lbsunroubd = floatval($this->weight);
      
        if($lbsunroubd > 1){
              //check if decimal
          
            if ($this->is_decimal($lbsunroubd)) {
            //check if less that .4
       
                if($this->decimalGetter($lbsunroubd)){
                    $lbs = $lbsunroubd;
               
                    $data['lbs']  = $lbs;
                }else{
                  $lbs = round($lbsunroubd);
              
                  $data['lbs']  = $lbs;
                }
            }else{
            
              $lbs = $lbsunroubd;
          
              $data['lbs']  = $lbs;
            }
            
          }else{
            $lbs = 1;
           
            $data['lbs']  = $lbs;
        }
        
        $special_handling = 0;
        $weight = $lbs;
        
        $length_inches = (float) $this->lenght;
        $width_inches = (float) $this->width;
        $height_inches = (float) $this->height;
        
        $special_handling = 0;
        // making the special handling fee 0 for now will be change if destiny gives us.
        if($this->type != "Normal"){
            $data['special_handling']  = 0;
            $special_handling = 0;
        }
      else{
            $data['special_handling'] = " NORMAL SIZE ";
            $special_handling = 0;
        }

        $length = $length_inches;
        $width = $width_inches;
        $height = $height_inches;
  
        $declared_value = $this->value;
        $dimensional_weight = (($length * $width * $height ) / 166);
        $dimensional_weight = number_format( (float) $dimensional_weight, 3, '.', '');

        $data['dimensional_weight']  = $dimensional_weight.' = '.$length .' x '. $width.' x '.$height.' / 166';

       // $dimensional_weight = number_format( (float) $dimensional_weight, 2, '.', '');

        $data['dimensional_weight_float']  = $dimensional_weight;
        

        $air_multiplier = $this->multipliers_air();
        $sea_multiplier = $this->multipliers_sea();


        $air_cargo = $weight *  $air_multiplier ; //7.99;
        $sea_cargo = $weight *  $sea_multiplier ; //2.75;
        $air_cargo_dw = $dimensional_weight * $air_multiplier ; //7.99;
        $sea_cargo_dw = $dimensional_weight *  $sea_multiplier ; //2.75;

        $data['air_cargo']  = $air_cargo.' = '.$weight.' x '. $air_multiplier ;
        $data['sea_cargo']  =  $sea_cargo.' = '.$weight .' x  '. $sea_multiplier ;
        $data['air_cargo_dw']  = $air_cargo_dw.' = '.$dimensional_weight.' x '. $air_multiplier ;
        $data['sea_cargo_dw']  =  $sea_cargo_dw.' = '.$dimensional_weight .' x  '. $sea_multiplier ;
    
        $data['custom_tax'] = $this->checkCustomsTax();
        $custom_tax =  $data['custom_tax'];
      

        if($weight > $dimensional_weight){
          $data['weight_txt']  = 'weight: '.$weight.' GREATER THAN  dimensional_weight: '.$dimensional_weight;
         // $data['weight_txt'] = "weight is greater than dimentional weight";
          $data['sea_cargo_converted'] = $sea_cargo;
          $data['air_cargo_converted'] = $air_cargo;

          $handling_fee_air =  0; 
          $handling_fee_sea =  0;
            
          $subtotal_air = $air_cargo + $special_handling + $handling_fee_air + $custom_tax ;
          $subtotal_sea = $sea_cargo + $special_handling + $handling_fee_sea + $custom_tax ;

          $initial_air =$air_cargo;
          $initial_sea =$sea_cargo;
        }
        else if($weight < $dimensional_weight){
           $data['weight_txt'] = 'weight: '.$weight.' | LESS THAN | dimensional weight: '.$dimensional_weight;
         //  $data['weight_txt'] = "weight is less than dimentional weight";             
           $data['sea_cargo_converted'] = $sea_cargo_dw;
           $data['air_cargo_converted'] = $air_cargo_dw;

           $handling_fee_air = 0; //$requestData->getPrice($this->product_value * 0.1);
           $handling_fee_sea = 0; //$requestData->getPrice($this->product_value * 0.1);

           $subtotal_air = $air_cargo_dw + $special_handling + $handling_fee_air  + $custom_tax ;
           $subtotal_sea = $sea_cargo_dw + $special_handling + $handling_fee_sea  + $custom_tax ;

            $initial_air =$air_cargo_dw;
            $initial_sea =$sea_cargo_dw;
        }
        $settingModel = new SettingModel();
        $getSettings = $settingModel->get();
        $dollar = $getSettings[0]['VALUE'];

        
        $local_shipping_fee =  0;

        $finalsubtotal_sea =$subtotal_sea;
        $finalsubtotal_sea =  $finalsubtotal_sea + $local_shipping_fee;
        $convenient_fee_sea = 0;
       
        $finalsubtotal_air =$subtotal_air;
        $finalsubtotal_air = $finalsubtotal_air + $local_shipping_fee;
        $convenient_fee_air = 0;

      
        $finalsubtotal_sea = $finalsubtotal_sea  + $convenient_fee_sea;
        $finalsubtotal_air = $finalsubtotal_air  + $convenient_fee_air;



        $data['local_shipping_fee'] =  $local_shipping_fee;
        $data['convenient_fee_air'] =  $convenient_fee_air;
        $data['convenient_fee_sea'] =  $convenient_fee_sea;
        $data['custom_tax'] =  "P".$custom_tax;

        $data['sea-cargo']  =  '<h3>P'. number_format($finalsubtotal_sea, 2, '.', ',').'</h3> = ';
        $data['air-cargo']  =  '<h3>P'.number_format($finalsubtotal_air, 2, '.', ',').'</h3> = ';
        

        $data['sea_cargo']  =  number_format($finalsubtotal_sea, 2, '.', ',').'';
        $data['air_cargo']  =  number_format($finalsubtotal_air, 2, '.', ',').'';

//  for initial price get 2 decimals
        $data['sea_cargo_saved'] = number_format((float)$finalsubtotal_sea, 2, '.', '');
        $data['air_cargo_saved'] = number_format((float)$finalsubtotal_air, 2, '.', '');

        $data['convenient_fee_air_saved'] =  number_format((float)$convenient_fee_air, 2, '.', '');
        $data['convenient_fee_sea_saved'] =   number_format((float)$convenient_fee_sea, 2, '.', '');
        $data['custom_tax_saved'] =  number_format((float)$custom_tax, 2, '.', '');
        $data['product_value_saved'] =  number_format((float)$product_values, 2, '.', '');
 // ----------------------------------------

        $data['handling_fee_air']  = $handling_fee_air;
        $data['handling_fee_sea']  = $handling_fee_sea;

        $data['special_handling_fee']  = $special_handling;

        $data['initial_sea']  =  number_format($initial_sea, 2, '.', ',');  
        $data['initial_air']  =  number_format($initial_air, 2, '.', ',');

        $data['sea-cargo-offical']  =  number_format($finalsubtotal_sea, 2, '.', ',');
        $data['air-cargo-offical']  =  number_format($finalsubtotal_air, 2, '.', ',');

        $data['dollar-convertion']  = $dollar;

      

        return $data;

  
    }

    private function is_decimal( $val ) {    
        return is_numeric( $val ) && floor( $val ) != $val;
    }


    private function decimalGetter($num){
        
        $whole = floor($num);  
        $fraction = (float) $num - $whole;
        return ($fraction < 0.5) ? true : false;
    }

    private function checkCustomsTax(){
        $settingModel = new SettingModel();
        $getSettings = $settingModel->get();
        $dollar = $getSettings[0]['VALUE'];

        $pesoPrice = floatval($this->product_value * $dollar);
        if($pesoPrice >= 10001){
            return $pesoPrice * 0.1;
        }
        else
        {
            return 0;
        }
    }


    private function multipliers_air()
    {
        return 480;
    }

    private function multipliers_sea()
    {
        // $data =  new SettingsData();
        // $data = $data->getSettings();
        return 199;
    }



}
