<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoyaltyAgreement extends Model
{

    public $table = "royalty_agreement";
    public $primaryKey = "royalty_agreement_id";

    /**
     * Get the book associated with this agreement
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Get the rates associated with this agreement
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function royaltyRate()
    {
        return $this->hasOne('App\RoyaltyRate', 'royalty_agreement_id');
    }

    /**
     * Get the recipient of this agreement
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function royaltyRecipient()
    {
        return $this->hasOne('App\RoyaltyRecipient', 'royalty_agreement_id');
    }
}

