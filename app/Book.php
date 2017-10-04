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

    /**
     * Get the list of years since publication of this book.
     *
     * @return array
     */
    private function getYearsActive()
    {
        $years = [];
        $max_year = date("Y");
        $min_year = date("Y", strtotime($this->publication_date));
        
        for ($y = $min_year; $y <= $max_year; $y++) {
            $years[] = $y;
        }
        
        return $years;
    }

    /**
     * Obtain a multidimensional array in the form of
     * [(integer)Year => (float)NetRevenue].
     *
     * @return array
     */
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

    /**
     * Obtain a multidimensional array in the form of
     * [(integer)Year => (float)totalRevenue].
     *
     * @return array
     */
    private function getTotalRevenueByYear()
    {
        $revenue = [];
        foreach ($this->years_active as $y) {
            if (!isset($revenue[$y])) {
                $revenue[$y] = 0.00;
            }
            
            $total = $this->getTotalRevenue($y);
            $revenue[$y] += $total;
        }
        
        return $revenue;
    }

    /**
     * Obtain a multidimensional array in the form of
     * [(integer)Year => (float)Income].
     *
     * @return array
     */
    private function getNonSalesIncomeByYear()
    {
        $revenue = [];
        foreach ($this->years_active as $y) {
            if (!isset($revenue[$y])) {
                $revenue[$y] = 0.00;
            }
            
            $total = $this->getNonSalesIncome($y);
            $revenue[$y] += $total;
        }
        
        return $revenue;
    }

    /**
     * Obtain a multidimensional array in the form of
     * [(integer)Year => (float)Costs].
     *
     * @return array
     */
    private function getNonSalesCostsByYear()
    {
        $revenue = [];
        foreach ($this->years_active as $y) {
            if (!isset($revenue[$y])) {
                $revenue[$y] = 0.00;
            }
            
            $total = $this->getNonSalesCosts($y);
            $revenue[$y] += $total;
        }
        
        return $revenue;
    }

    /**
     * Obtain the total net revenue for this book in a given year.
     *
     * @param int $year
     * @return float
     */
    private function getTotalNetRevenue($year)
    {
        $result =  DB::table('sale')
            ->join('volume', 'sale.volume_id', '=', 'volume.volume_id')
            ->join('book', 'volume.book_id', '=', 'book.book_id')
            ->select(DB::raw('SUM(`net_revenue`) as total'))
            ->whereYear('sale_date', $year)
            ->where('doi', '=', $this->doi)
            ->first();

        return $result->total !== null ? (float) $result->total : 0.00;

    }

    /**
     * Obtain the total sales revenue for this book in a given year.
     *
     * @param int $year
     * @return float
     */
    private function getTotalRevenue($year)
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

    /**
     * Obtain the total extra income (not obtained from sales)
     * for this book in a given year.
     *
     * @param int $year
     * @return float
     */
    private function getNonSalesIncome($year)
    {
        return $this->getSubventions("income", $year);

    }

    /**
     * Obtain the total extra income (not obtained from sales)
     * for this book in a given year.
     *
     * @param int $year
     * @return float
     */
    private function getNonSalesCosts($year)
    {
        return $this->getSubventions("cost", $year);

    }

    /**
     * Obtain the total positive or negative subventions 
     * for this book in a given year.
     *
     * @param string $type
     * @param int $year
     * @return float
     */
    private function getSubventions($type, $year)
    {
        switch ($type) {
            case "cost":
                $symbol = "<";
                break;
            default:
            case "income":
                $symbol = ">";
                break;
        }
 
        $result =  DB::table('subvention')
            ->join('book', 'book.book_id', '=', 'subvention.book_id')
            ->select(DB::raw('SUM(`subvention_value`) as total'))
            ->whereYear('subvention_date', $year)
            ->where([
                ['subvention_value', $symbol, 0],
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

    /**
     * Obtain the total readership of a particular measure in a given year
     * for this book.
     *
     * @param string $measure
     * @param int $year
     * @return int
     */
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

    /**
     * Obtain the total this books sales in a given year for a particular
     * book format (e.g. epub, hardback, pdf)
     *
     * @param string $format
     * @param int $year
     * @return int
     */
    private function getTotalSalesFormatYear($format, $year)
    {
        $result = DB::table('sale')
            ->join('volume','sale.volume_id', '=', 'volume.volume_id')
            ->join('book','volume.book_id', '=', 'book.book_id')
            ->select( DB::raw('SUM(`sales_units`) as sales'))
            ->whereYear('sale_date', $year)
            ->where([['doi', '=', $this->doi], ['format_name', '=', $format]])
            ->first();
        
        return $result->sales !== null ? $result->sales : 0;
    }

    /**
     * Obtain the total this books sales in a given year
     *
     * @param int $year
     * @return int
     */
    private function getTotalSalesByYear($year)
    {
        $result = DB::table('sale')
            ->join('volume','sale.volume_id', '=', 'volume.volume_id')
            ->join('book','volume.book_id', '=', 'book.book_id')
            ->select( DB::raw('SUM(`sales_units`) as sales'))
            ->whereYear('sale_date', $year)
            ->where('doi', '=', $this->doi)
            ->first();
        
        return $result->sales !== null ? $result->sales : 0;
    }
}
