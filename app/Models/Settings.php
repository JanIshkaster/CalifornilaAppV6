<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'handling_fee',
        'custom_tax', 
        'convenience_fee', 
        'credit_card_fee', 
        'dollar_conversion',
        'admin_emails' 
    ]; 

}
