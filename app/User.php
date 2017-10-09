<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $table = "user";
    public $primaryKey = "user_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password', 'orcid',
        'twitter', 'repositories'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the author associated with this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function author()
    {
        return $this->hasOne('App\Author', 'user_id');
    }

    /**
     * Determine if the user is an author
     *
     * @return boolean
     */
    public function isAuthor()
    {
        return $this->author->count() > 0;
    }

    /**
     * Determine if the user is an administrator
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin ? true : false;
    }

    /**
     * Check if the user can read royalty information for a given book
     *
     * @param int $book_id
     * @return boolean
     */
    public function hasAccessToRoyaltyOfBook($book_id)
    {
        return $this->isAdmin() || $this->author->hasRoyaltyOfBook($book_id);
    }
}
