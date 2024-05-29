<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    // SETTINGS PAGE VIEW
    public function settings(){ 

        $adminSettings = Settings::all();

        return view('settings', ['adminSettings' => $adminSettings]);
    } 
    
    // SETTINGS FORM 
    public function settings_form(Request $request){
        try {

            // Start a database transaction
            DB::beginTransaction(); 

            // Validate the incoming request...
            $validatedData = $request->validate([
                'handling_fee' => 'nullable|numeric|min:0',
                'custom_tax' => 'nullable|numeric|min:0',
                'convenience_fee' => 'nullable|numeric|min:0',
                'credit_card_fee' => 'nullable|numeric|min:0',
                'dollar_conversion' => 'nullable|numeric|min:0',
                'admin_emails' => 'required|email',
            ]); 

            // Save data
            Settings::create($validatedData);
            
            // Commit the transaction if everything is successful
            DB::commit();
    
            return redirect()->back()->with('success', 'Settings saved successfully.');
        } catch (ValidationException $e) {
            // Redirect back with validation errors if validation fails
            return redirect()->back()->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            // Rollback the transaction on any other exception
            DB::rollback();
            
            // Handle or log the exception as needed
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }
    

}
