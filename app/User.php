<?php

namespace App;

use Illuminate\Support\Facades\DB;
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
        return $this->author !== null;
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
     * Get the name of this user
     *
     * @return string
     */
    public function fullName()
    {
        return $this->name . " " . $this->surname;
    }

    /**
     * Check if the user can read royalty information for a given book
     *
     * @param int $book_id
     * @return boolean
     */
    public function hasAccessToSalesOfBook($book_id)
    {
        return $this->isAdmin()
            || $this->author->isContributorOfBook($book_id);
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

    /**
     * Get the details of a user from the external users database
     *
     * @param string $email
     * @return Collection
     */
    public static function getUserRecord($email)
    {
        return DB::connection('mysql-users')
            ->table('jss_customers')
            ->where('email', '=', $email)
            ->first();
    }

    /**
     * Add the user ID of the local user account to the remote user table
     *
     * @param int $remoteId
     * @param int $localId
     * @return type
     */
    public static function linkAccounts($remoteId, $localId)
    {
        return DB::connection('mysql-users')
            ->table('jss_customers')
            ->where('customerID', '=', $remoteId)
            ->update(['user_id' => $localId]);
    }
}
