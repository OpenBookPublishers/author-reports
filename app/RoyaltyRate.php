<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoyaltyRate extends Model
{

    public $table = "royalty_agreement";
    public $primaryKey = "royalty_agreement_id";

    /**
     * Get the agreement associated with this rate
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function royaltyAgreement()
    {
        return $this->belongsTo('App\RoyaltyAgreement');
    }
}

