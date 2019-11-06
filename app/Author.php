<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    public $table = "author";
    public $primaryKey = "author_id";
    public $timestamps = false;
    protected $hidden = ['deceased'];

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
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the royalty recipient associated with this author
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function royaltyRecipients()
    {
        return $this->belongsToMany('App\RoyaltyRecipient',
            'royalty_recipient_author',
            'author_id',
            'royalty_recipient_id');
    }

    /**
     * Determine if this author has a royalty agreement.
     *
     * @return bool
     */
    public function receivesRoyalties()
    {
        return $this->royaltyRecipients !== null;
    }

    /**
     * Determine if this author has contributed to the give book
     *
     * @param int $book_id
     * @return boolean
     */
    public function isContributorOfBook($book_id)
    {
        return $this->belongsToMany('App\Book', 'book_author',
                                    'author_id', 'book_id')
                    ->where('book_author.book_id', $book_id)
                    ->count() > 0;
    }

    /**
     * Determine if this author receives royalties of a given book
     *
     * @param int $book_id
     * @return boolean
     */
    public function hasRoyaltyOfBook($book_id)
    {
        foreach ($this->royaltyRecipients as $royaltyRecipient) {
            if ($royaltyRecipient->hasAgreementOfBook($book_id)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine if this author receives royalties from a given agreement
     *
     * @param int $agreement_id
     * @return boolean
     */
    public function hasAgreement($agreement_id)
    {
        foreach ($this->royaltyRecipients as $royaltyRecipient) {
            if ($royaltyRecipient->royalty_agreement_id === $agreement_id) {
                return true;
            }
        }
        
        return false;
    }

    public static function allWithRoyalties()
    {
        $author_ids = DB::table('royalty_recipient_author')
            ->select(DB::raw('DISTINCT(author_id) AS author_id'))
            ->pluck('author_id');

        return self::whereIn('author_id', $author_ids)
            ->orderBy('author_name')
            ->get();
    }

    /**
     * Remove special characters from author's name
     * and replace spaces with underscores.
     *
     * @return string
     */
    public function sanitisedName()
    {
        $sane_name = filter_var($this->author_name, FILTER_SANITIZE_STRING,
                       FILTER_FLAG_STRIP_HIGH);
        return str_replace(' ', '_', $sane_name);
    }

    public function loadRoyalties()
    {
        $royalties = [];
        foreach ($this->royaltyRecipients as $recipient) {
            $result
              = $recipient->royaltyAgreement->calculateTotalRoyalties();
            $result['base_unit'] = $recipient
                ->royaltyAgreement->royaltyRate->base_unit;
            $result['threshold_unit'] = $recipient
                ->royaltyAgreement->royaltyRate->threshold_unit;
            $result['rate'] = $recipient
                ->royaltyAgreement->royaltyRate->rate;
            $result['royalty_agreement_id'] = $recipient
                ->royaltyAgreement->royalty_agreement_id;
            $royalties[] = $result;
        }
        $this->royalties_arising = 0.00;
        $this->royalties_donated = 0.00;
        $this->royalties_paid = 0.00;
        $this->amount_due = 0.00;
        foreach ($royalties as $royalty) {
            $this->royalties_arising += $royalty['Royalties arising'];
            $this->royalties_donated += $royalty['Royalties donated'];
            $this->royalties_paid += $royalty['Royalties paid'];
        }
        $this->amount_due = $this->royalties_arising
                - $this->royalties_donated
                - $this->royalties_paid;
        $this->royalties = $royalties;
    }
}
