<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public $table = "book";
    public $primaryKey = "book_id";
    public $years_active;

    private $stats_measures_aliases = [
        "(Grokstat) Daily Views" => "Wikimedia",
        "(GoogleAnalytics) HTML Visits" => "OBP HTML Reader",
        "(GoogleAnalytics) Scribd Visits" => "OBP PDF Reader",
        "(WorldReader) Total Opens" => "World Reader",
        "(WorldReaderWeb) Total Opens" => "World Reader",
        "(OpenEdition) Total Book Views" => "Open Edition",
        "(GoogleBooks) Book Visits (BV)" => "Google Books",
        "(Ungluit) Downloads" => "Unglue.it",
        "(ClassicsLibrary) Sessions" => "Classics Library",
        "(Internet Archive) Views" => "Internet Archive"
    ];
    private $downloads_measures_aliases = [
        "(DownloadLogs) Downloads" => "OBP Website",
        "(SalesCSVDownloads) Downloads" => "Retail Distributors"
    ];
    
    public function __construct()
    {
        Parent::__construct();
        $this->years_active = $this->getYearsActive();
    }

    /**
     * Get the authors associated with this book
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors()
    {
        return $this->belongsToMany('App\Author', 'book_author',
                                    'book_id', 'author_id');
    }

    /**
     * Get the volumes associated with this book
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function volumes()
    {
        return $this->hasMany('App\Volume');
    }

    /**
     * Get the subventions associated with this book
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function subventions()
    {
        return $this->hasMany('App\Subvention');
    }

    /**
     * Get the royalty agreements associated with this book
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function royaltyAgreements()
    {
        return $this->hasMany('App\RoyaltyAgreement');
    }
    
    private function getYearsActive()
    {
        $years = [];
        $max_year = date("Y");
        $min_year = date("Y", strtotime($this->publication_date));
        
        for ($y = $min_year; $y <= $max_year; $y++) {
            if ($y === 2017) {
                continue;
            }
            $years[] = $y;
        }
        
        return $years;
    }
    
    private function getTotalNetRevenueByYear()
    {
        $revenue = [];
        foreach ($this->years_active as $y) {
            if (!isset($revenue[$y])) {
                $revenue[$y] = 0.00;
            }
            
            $total = $this->getTotalNetRevenue($y);
            $revenue[$y] += $total;
        }
        
        return $revenue;
    }
    
    private function getTotalNetRevenue($year)
    {
        $result =  DB::table('sale')
            ->join('volume', 'sale.volume_id', '=', 'volume.volume_id')
            ->join('book', 'volume.book_id', '=', 'book.book_id')
            ->select(DB::raw('SUM(`sales_revenue`) as total'))
            ->whereYear('sale_date', $year)
            ->where('doi', '=', $this->doi)
            ->first();

        return $result->total !== null ? (float) $result->total : 0.00;

    }
    
    private function getNonSalesIncome($year)
    {
        $result =  DB::table('subvention')
            ->join('book', 'book.book_id', '=', 'subvention.book_id')
            ->select(DB::raw('SUM(`subvention_value`) as total'))
            ->whereYear('subvention_date', $year)
            ->where([
                ['subvention_value', '>', 0],
                ['doi', '=', $this->doi]])
            ->first();
        
        return $result->total !== null ? (float) $result->total : 0.00;

    }
    
    private function getReadershipPerCountry()
    {
        return DB::connection('mysql-stats')
            ->table('EventMeasurements')
            ->join('Events','EventMeasurements.event_id', '=',
                'Events.event_id')
            ->join('Countries', 'Countries.country_id', '=',
                'Events.country_id')
            ->join('Doi', 'Events.book_id', '=', 'Doi.book_id')
            ->select( DB::raw('SUM(`value`) as readership'), 'country_name')
            ->where([
                ['doi', '=', $this->doi], 
                ['country_name', '<>', "(not set)"]])
            ->groupBy('country_name')
            ->orderBy('readership')
            ->get();
    }
    
    private function getReadershipPerContinent()
    {
        return DB::connection('mysql-stats')
            ->table('EventMeasurements')
            ->join('Events','EventMeasurements.event_id', '=',
                'Events.event_id')
            ->join('Countries', 'Countries.country_id', '=',
                'Events.country_id')
            ->join('Doi', 'Events.book_id', '=', 'Doi.book_id')
            ->select( DB::raw('SUM(`value`) as readership'), 'continent_code')
            ->where([
                ['doi', '=', $this->doi], 
                ['continent_code', '<>', "--"]])
            ->groupBy('continent_code')
            ->orderBy('readership')
            ->get();
    }
    
    private function getReadershipMeasureYear($measure, $year)
    {
        $result = DB::connection('mysql-stats')
            ->table('EventMeasurements')
            ->join('Measures','EventMeasurements.measure_id', '=',
                'Measures.measure_id')
            ->join('Events','EventMeasurements.event_id', '=',
                'Events.event_id')
            ->join('Countries', 'Countries.country_id', '=',
                'Events.country_id')
            ->join('Doi', 'Events.book_id', '=', 'Doi.book_id')
            ->select( DB::raw('SUM(`value`) as readership'))
            ->whereYear('timestamp', $year)
            ->where([
                ['measure_description', '=', $measure],
                ['doi', '=', $this->doi]])
            ->first();
        
        return $result->readership !== null ? $result->readership : 0;
    }
}
