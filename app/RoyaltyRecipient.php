<?php

namespace App;

use DB;
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
     * Get the royalty payments made to this recipient
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function royaltyPayments()
    {
        return $this->hasMany('App\RoyaltyPayment',
            'royalty_recipient_id',
            'royalty_recipient_id');
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

    /**
     * Get all payments made to this recipient.
     *
     * @return float
     */
    public function getTotalPayments()
    {
        $result =  DB::table('royalty_payment')
            ->select(DB::raw('SUM(`payment_value`) as total'))
            ->where('royalty_recipient_id', '=', $this->royalty_recipient_id)
            ->first();

        return $result->total !== null ? (float) $result->total : 0.00;
    }

    /**
     * Get all payments made to this recipient on a given year
     *
     * @param int $year
     * @return float
     */
    public function getTotalPaymentsInYear($year)
    {
        $result =  DB::table('royalty_payment')
            ->select(DB::raw('SUM(`payment_value`) as total'))
            ->whereYear('payment_date', $year)
            ->where('royalty_recipient_id', '=', $this->royalty_recipient_id)
            ->first();

        return $result->total !== null ? (float) $result->total : 0.00;
    }
}
