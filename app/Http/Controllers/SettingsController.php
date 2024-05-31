<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    // SETTINGS PAGE VIEW
    public function settings(){ 

        $adminSettings = Settings::first();

        return view('settings', ['adminSettings' => $adminSettings]);
    } 
    
    // SETTINGS FORM
    public function settings_form(Request $request) {  
        try {
            // Validate the incoming request...
            $validatedData = $request->validate([
                'handling_fee' => 'nullable|numeric|min:0',
                'custom_tax' => 'nullable|numeric|min:0',
                'convenience_fee' => 'nullable|numeric|min:0',
                'credit_card_fee' => 'nullable|numeric|min:0',
                'dollar_conversion' => 'nullable|numeric|min:0',
                'admin_emails' => 'required|email',
            ]);

            // Start a database transaction
            DB::beginTransaction();

            // Find or create a Settings record based on admin_emails
            $settings = Settings::updateOrCreate(
                ['admin_emails' => $validatedData['admin_emails']],
                $validatedData
            );

            // Commit the transaction if everything is successful
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Settings saved successfully.');
        } catch (ValidationException $e) {
            // Rollback the transaction on any other exception
             DB::rollback();

            // Log validation errors
            Log::error('Validation errors', ['errors' => $e->validator->errors()]);
            return redirect()->back()->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            // Rollback the transaction on any other exception
            DB::rollback();

            // Log other exceptions
            Log::error('An error occurred', ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    

}
