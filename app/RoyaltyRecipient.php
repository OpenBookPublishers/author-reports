<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoyaltyRecipient extends Model
{

    public $table = "royalty_recipient";
    public $primaryKey = "royalty_recipient_id";

    /**
     * Get the agreement associated with this recipient
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function royaltyAgreement()
    {
        return $this->belongsTo('App\RoyaltyAgreement',
            'royalty_agreement_id');
    }

    /**
     * Get the author associated with this recipient
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function author()
    {
        return $this->belongsToMany('App\Author',
            'royalty_recipient_author',
            'royalty_recipient_id',
            'author_id');
    }

    /**
     * Determine if this recipient has an agreement for a given book
     *
     * @param int $book_id
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function hasAgreementOfBook($book_id)
    {
        return $this->belongsTo('App\RoyaltyAgreement', 'royalty_agreement_id')
                    ->where('book_id', $book_id)
                    ->count() > 0;
    }
}
