<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerAddedProduct;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','first_name', 'last_name', 'email', 'phone'];

    public function addedProducts(){
        return $this->hasMany(CustomerAddedProduct::class);
    }


}
