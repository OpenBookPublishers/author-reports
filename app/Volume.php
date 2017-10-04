<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volume extends Model
{

    public $table = "volume";
    public $primaryKey = "volume_id";

    /**
     * Get the book associated with the volume
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Get the sales of this volume
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function sales()
    {
        return $this->hasMany('App\Sale');
    }
}

