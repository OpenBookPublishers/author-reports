<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CreatePassword as CreatePasswordNotification;
use App\Notifications\CustomResetPassword as ResetPasswordNotification;

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
     * Determine whether this user wants to keep sales data private.
     *
     * @return boolean
     */
    public function wantsSalesDataPrivate()
    {
        return $this->display_sales === 0 ? true : false;
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
     * Send the password reset notification
     *
     * @see https://laracasts.com/discuss/channels/laravel/how-to-override-message-in-sendresetlinkemail-in-forgotpasswordcontroller
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Notify the user that an account has been created, with a link
     * to create a password.
     *
     * @see https://laravel.com/docs/5.4/notifications#sending-notifications
     * @param string $token
     * @return void
     */
    public function sendNewAccountNotification($token)
    {
        $this->notify(new CreatePasswordNotification($token));
    }
}
