<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketAdditionalFees extends Model
{
    protected $fillable = ['ticket_id', 'amount'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
