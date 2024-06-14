<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketTrackingCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 
        'tracking_code',
        'tracking_link', 
    ];

    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

}
