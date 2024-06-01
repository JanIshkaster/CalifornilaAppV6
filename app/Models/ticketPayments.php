<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 
        'total_handling_fee',
        'total_custom_tax',
        'total_convenience_fee',
        'total_credit_card_fee',
        'total_product_value',
        'total_product_price', 
        'payment_type'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
