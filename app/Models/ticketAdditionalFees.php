<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketAdditionalFees extends Model
{

    use HasFactory;

    protected $fillable = ['ticket_id', 'amount', 'fee_data_details'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
