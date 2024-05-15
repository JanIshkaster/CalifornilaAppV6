<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class customerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'street',
        'region', 
        'city',
        'provice', 
        'barangay', 
        'zipcode',
        'birthdate',
        'gender',
        'contact',
        'hear'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
