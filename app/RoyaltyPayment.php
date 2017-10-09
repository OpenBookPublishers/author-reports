<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoyaltyPayment extends Model
{

    public $table = "royalty_payment";
    public $primaryKey = "royalty_payment_id";

    /**
     * Get the recipient of this payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function royaltyRecipient()
    {
        return $this->belongsTo('App\RoyaltyRecipient');
    }
}

