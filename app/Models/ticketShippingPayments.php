<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketShippingPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 
        'shopify_product_sp_id', 
        'image_path',
        'total_shipping_value'
    ];

    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

}
