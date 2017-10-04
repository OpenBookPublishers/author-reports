<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

    public $table = "sale";
    public $primaryKey = "sale_id";

    /**
     * Get the volume associated with the sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function volume()
    {
        return $this->belongsTo('App\Volume');
    }
}
