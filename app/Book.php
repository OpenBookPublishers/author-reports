<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public $table = "book";
    public $primaryKey = "book_id";

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

    public function loadAllData()
    {
        $this->years_active = $this->getYearsActive();
        $this->loadCountryReadership();
        $this->loadContinentReadership();
        $this->loadReadership();
        $this->loadSales();
        $this->loadDownloads();
        $this->loadDownloads();
    }

    public function loadReadership()
    {
        $this->readership = $this->getStats("readership");
    }

    public function loadSales()
    {
        $this->sales = $this->getStats("sales");
    }

    public function loadDownloads()
    {
        $this->downloads = $this->getStats("downloads");
    }

    /**
     * 
     *
     * @todo replace env array with proper table
     * @param App\RoyaltyAgreement $agreement
     */
    public function calculateRoyaltiesInAgreement($agreement)
    {
        $royalties   = [];
        $total_sales = 0;
        $total_net   = 0;

        $royalties['Net Sales Rev']    = $this->getTotalNetRevenueByYear();
        $royalties['Non-sales income'] = $this->getNonSalesIncomeByYear();
        $royalties['Non-sales costs']  = $this->getNonSalesCostsByYear();
        $royalties['Net Rev Total']    = [];

        foreach ($this->years_active as $y) {
            $salesThisYear = $this->getTotalSalesByYear($y);
            $total_sales += $salesThisYear;

            $royalties['Royalties arising'][$y] = 0.00;
            $royalties['Net Rev Total'][$y] =
                $royalties['Non-sales income'][$y]
                + $royalties['Non-sales costs'][$y]
                + $royalties['Net Sales Rev'][$y];
            $total_net += isset($royalties['Net Rev Total'][$y])
                ? $royalties['Net Rev Total'][$y]
                : 0.00;

            $royalty = $agreement->royaltyRate->calculate(
                $royalties['Net Rev Total'][$y], $total_net, $salesThisYear);
            $royalties['Royalties arising'][$y] += $royalty;

            $royalties['Royalties paid'][$y] = $agreement->royaltyRecipient
                ->getTotalPayments($y);
            $royalties['Amount due'][$y] = $royalties['Royalties arising'][$y]
                - $royalties['Royalties paid'][$y];
        }

        return $royalties;
    }

    /**
     * Load the countries array
     */
    public function loadCountryReadership()
    {
        $countries  = [];
        $data = $this->getReadershipPerCountry();
        $count = count($data) - 1;
        $total = 0;
        $other = 0;

        foreach ($data as $key => $result) {
            if ($key <= $count - 10) {
                $other += $result->readership;
            } else {
                $countries[$result->country_name] = $result->readership;
            }
            $total += $result->readership;
        }
        $countries['Other'] = $other;
        asort($countries);
        $this->countries = $countries;
        $this->total_country_readership = $total;
        $this->total_countries = count($data);
    }

    /**
     * Load the continents array
     */
    public function loadContinentReadership()
    {
        $continents = [];
        $data = $this->getReadershipPerContinent();
        foreach ($data as $result) {
            $continents[$result->continent_code] = $result->readership;
        }
        $this->continents = $continents;
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
        return $this->hasMany('App\Volume', 'book_id');
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
        return $this->hasMany('App\RoyaltyAgreement', 'book_id', 'book_id');
    }

    /**
     * Determine if this book has any royalties
     *
     * @return boolean
     */
    public function hasRoyalty()
    {
        return $this->royaltyAgreements->count() > 0;
    }

    /**
     * Determine if this book has been published
     *
     * @return boolean
     */
    public function isPublished()
    {
        return $this->publication_date !== null
          && date("Y-m-d", strtotime($this->publication_date)) <= date("Y-m-d");
    }

    /**
     * Generate a message to indicate that a book has not been published yet.
     *
     * @return string
     */
    public function getNotPublishedMessage()
    {
        return $this->title . " has not been published.";
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
     * Get the list of formats that this book is available in.
     *
     * @return array
     */
    private function getFormats()
    {
        $formats = [];
        foreach ($this->volumes as $volume) {
            $formats[] = $volume->format_name;
        }
        
        return $formats;
    }

    private function getMeasures($type)
    {
        switch ($type) {
            case "readership": return $this->stats_measures_aliases;
            case "downloads":  return $this->downloads_measures_aliases;
            case "sales":      return $this->getFormats();
        }
    }

    private function getMeasurement($type, $measure, $alias, $year)
    {
        switch ($type) {
            case "readership":
            case "downloads":
                return $this->getReadershipMeasureYear($measure, $year);
            case "sales":
                return $this->getSalesFormatYear($alias, $year);
       }
    }

    private function getStats($type)
    {
        $stats = [];
        $measures = $this->getMeasures($type);

        foreach ($measures as $measure => $alias) {
            if (!isset($stats[$alias])) {
                $stats[$alias] = [];
            }

            foreach ($this->years_active as $y) {
                if (!isset($stats[$alias][$y])) {
                    $stats[$alias][$y] = 0;
                }

                $units = $this->getMeasurement($type, $measure, $alias, $y);
                $stats[$alias][$y] += $units;
            }

            // clean up
            $cleanup = true;
            foreach ($stats[$alias] as $year => $val) {
                if ($val !== 0) {
                    $cleanup = false;
                    break;
                 }
            }
            if ($cleanup) {
                unset($stats[$alias]);
            }
        }
        return $stats;
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
            ->get()
            ->toArray();
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
            ->get()
            ->toArray();
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
     * Obtain the total sales of this book in a given year for a particular
     * book format (e.g. epub, hardback, pdf)
     *
     * @param string $format
     * @param int $year
     * @return int
     */
    private function getSalesFormatYear($format, $year)
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
