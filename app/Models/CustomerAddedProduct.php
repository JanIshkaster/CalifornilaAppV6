<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class CustomerAddedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'warehouse_status',
         'quantity',
         'order_number',
         'merchant',
         'package_type',
         'value',
         'description',
         'status',
         'note',
         'product_image'
        ];

        public function customer(){
            return $this->belongsTo(Customer::class);
        }


}
