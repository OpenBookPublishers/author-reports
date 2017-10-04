<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    public $table = "author";
    public $primaryKey = "author_id";

    /**
     * Get the books associated with this author
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function books()
    {
        return $this->belongsToMany('App\Book', 'book_author',
                                    'author_id', 'book_id');
    }

    /**
     * Get the user associated with this author
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
