<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoyaltyRate extends Model
{

    public $table = "royalty_rate";
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

    public function calculate($net_year, $total_net, $total_sales)
    {
        switch ($this->threshold_unit) {
            case "Net Revenue":
                if ($net_year <= 0.00 || $total_net <= 0.00) {
                    return 0.00;
                }

                if ($total_net >= $net_year) {
                    return $this->rate * $net_year;
                } else {
                    return $this->rate * $total_net;
                }

            case "Sales":
                if ($total_sales < $this->threshold
                    || $net_year <= 0.00
                    || $total_net <= 0.00) {
                    return 0.00;
                }

                return $this->rate * $net_year;
        }
    }
}

