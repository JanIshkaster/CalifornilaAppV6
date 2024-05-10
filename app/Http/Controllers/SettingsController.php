<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // SETTINGS
    public function settings(Request $request){ 
        return view('settings');
    }

    // SETTINGS FORM
    public function settings_form(Request $request){
        // Handle the request...
        $data = $request->all(); 
        // You can now use the $data object or process it as needed

        // Pass data to the view
        return view('settings', ['formData' => $data]);
    }
}
