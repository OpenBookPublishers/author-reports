<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subvention extends Model
{

    public $table = "subvention";
    public $primaryKey = "subvention_id";

    /**
     * Get the book associated with the volume
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }
}

