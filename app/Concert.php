<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model {

    protected $guarded = [];
    protected $dates = ['date'];

    public function getFormattedDateAttribute() {
    	return $this->date->format('F j, Y');
    }

    public function getFormattedStartTimeAttribute() {
    	return $this->date->format('g:ia');
    }

    public function getTicketPriceInDollarsAttribute() {
    	return number_format($this->ticket_price / 100, 2);
    }

    public function scopePublished($query) {
        return $query->whereNotNull('published_at');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function orderTickets($email, $ticketQuantity) {
        $order = $this->orders()->create(['email' => $email]);
        
        for ($i=0; $i < $ticketQuantity; $i++) { 
            $order->tickets()->create([]);
        }

        return $order;
    }

}
