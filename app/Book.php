<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public $table = "book";
    public $primaryKey = "book_id";
    public $years_active;
    protected $hidden = ['book_id'];

    public $quarters = [
        1 => "First Quarter",
        2 => "Second Quarter",
        3 => "Third Quarter",
        4 => "Fourth Quarter"
    ];

    public function loadAllData($year = null)
    {
        $this->years_active = $this->getYearsActive();
        $this->loadReadership($year);
        $this->loadSales($year);
        $this->loadDownloads($year);
    }

    public function loadReadership($year = null)
    {
        $this->readership = $this->getStats("readership", $year);
    }

    public function loadSales($year = null)
    {
        $this->sales = $this->getSales("sales", $year);
    }

    public function loadDownloads($year = null)
    {
        $this->downloads = $this->getStats("downloads", $year);
    }

    /**
     *
     *
     * @todo replace env array with proper table
     * @param App\RoyaltyAgreement $agreement
     */
    public function calculateRoyaltiesInAgreement($agreement, $year)
    {
        $royalties   = [];
        $total_sales = 0;
        $total_net   = 0;

        if ($year === null) {
            $royalties['Net Sales Rev']    = $this->getTotalNetRevenueByYear();
            $royalties['Non-sales income'] = $this->getNonSalesIncomeByYear();
            $royalties['Non-sales costs']  = $this->getNonSalesCostsByYear();
            $royalties['Net Rev Total']    = [];

            $periods = $this->years_active;
        } else {
            $royalties['Net Sales Rev'] = 
                $this->getTotalNetRevenueByQuarter($year);
            $royalties['Non-sales income'] =
                $this->getNonSalesIncomeByQuarter($year);
            $royalties['Non-sales costs'] =
                $this->getNonSalesCostsByQuarter($year);
            $royalties['Net Rev Total'] = [];

            $periods = $this->quarters;
        }

        // When $periods == years_active $p will be the year, otherwise it will
        // be the number of the quarter. Functions like getTotalSalesByYear()
        // can take a year and an optional quarter; when we want quarterly data
        // we provide both ($year, $p).
        foreach ($periods as $p => $na) {
            $salesThisPeriod = $year === null ? $this->getTotalSalesByYear($p)
                : $this->getTotalSalesByYear($year, $p);
            $total_sales += $salesThisPeriod;

            $royalties['Royalties arising'][$p] = 0.00;
            $royalties['Net Rev Total'][$p] =
                $royalties['Non-sales income'][$p]
                + $royalties['Non-sales costs'][$p]
                + $royalties['Net Sales Rev'][$p];
            $total_net += isset($royalties['Net Rev Total'][$p])
                ? $royalties['Net Rev Total'][$p]
                : 0.00;

            $royalty = $agreement->royaltyRate->calculate(
              $royalties['Net Rev Total'][$p], $total_net, $salesThisPeriod);
            $royalties['Royalties arising'][$p] += $royalty;

            $royalties['Royalties donated'][$p] = $year === null
              ? $agreement->royaltyRecipient->getTotalPaymentsInYear($p,null,1)
              : $agreement->royaltyRecipient->getTotalPaymentsInYear($year,$p,1);
            $royalties['Royalties paid'][$p] = $year === null
              ? $agreement->royaltyRecipient->getTotalPaymentsInYear($p)
              : $agreement->royaltyRecipient->getTotalPaymentsInYear($year,$p);
            $royalties['Amount due'][$p] =
                $royalties['Royalties arising'][$p]
                - $royalties['Royalties donated'][$p]
                - $royalties['Royalties paid'][$p];
        }

        return $royalties;
    }

    /**
     *
     *
     * @todo replace env array with proper table
     * @param App\RoyaltyAgreement $agreement
     */
    public function calculateTotalRoyaltiesInAgreement($agreement)
    {
        $royalties   = [];
        $total_sales = $this->getTotalSales();
        $royalties['Net Sales Rev']    = $this->getTotalNetRevenue();
        $royalties['Non-sales income'] = $this->getNonSalesIncome();
        $royalties['Non-sales costs']  = $this->getNonSalesCosts();
        $royalties['Net Rev Total'] = $royalties['Non-sales income']
            + $royalties['Non-sales costs']
            + $royalties['Net Sales Rev'];
        $royalties['Royalties arising']=$agreement->royaltyRate->calculate(
                $royalties['Net Rev Total'],
                $royalties['Net Sales Rev'],
                $total_sales
            );
        $royalties['Royalties donated'] = $agreement->royaltyRecipient
                ->getTotalPayments(1);
        $royalties['Royalties paid'] = $agreement->royaltyRecipient
                ->getTotalPayments();
        $royalties['Amount due'] = $royalties['Royalties arising']
                - $royalties['Royalties donated']
                - $royalties['Royalties paid'];

        return $royalties;
    }

    /**
     * Get the authors associated with this book
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors()
    {
        return $this->belongsToMany('App\Author', 'book_author',
                                    'book_id', 'author_id')
                    ->withPivot('role_name');
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
     * Determine if we have authors' agreement to display sales data publicly.
     *
     * @return boolean
     */
    public function areSalesPublic()
    {
        $public = true;
        foreach ($this->authors as $author) {
            if (isset($author->user)
                && $author->user->wantsSalesDataPrivate()) {
                $public = false;
            }
        }
        return $public;
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
        return $this->title . " has not been published yet.";
    }

    /**
     * Get the DOI prefix
     *
     * @return string
     */
    public function getDoiPrefix()
    {
        return explode("/", $this->doi)[0];
    }

    /**
     * Get the DOI suffix
     *
     * @return string
     */
    public function getDoiSuffix()
    {
        return explode("/", $this->doi)[1];
    }

    /**
     * Remove special characters from book title
     * and replace spaces with underscores.
     *
     * @return string
     */
    public function sanitisedTitle()
    {
        $sane_title = filter_var($this->title, FILTER_SANITIZE_STRING,
                       FILTER_FLAG_STRIP_HIGH);
        return str_replace(' ', '_', $sane_title);
    }

    /**
     * Get the list of years and months since publication of this book.
     *
     * @return array
     */
    public function getYearsActive()
    {
        $years = [];
        $cur_date = $this->publication_date;
        $end = date("Y-m-d");
        $d = "01"; // we want whole months, so we start counting from the first

        do {
            $y = date("Y", strtotime($cur_date));
            $m = date("m", strtotime($cur_date));

            if (!isset($years[$y])) {
                $years[$y] = [];
            }

            if (!isset($years[$y][$m])) {
                $years[$y][$m] = [];
            }

            $cur_date = date("Y-m-d",
                strtotime("+1 month", strtotime($y . "-" . $m . "-". $d)));
        } while ($cur_date < $end);

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

    private function getStats($type, $year = null)
    {
        $stats = [];
        $data = $this->getReadershipByMeasureYear($year);
        $key = $year === null ? "year" : "month";
        $downloads = $type === "downloads";

        foreach ($data as $measure) {
            $is_download = $measure->type === "downloads";
            if (($downloads && !$is_download) ||
                (!$downloads && $is_download)) {
                continue;
            }
            $source = $measure->source . " " . ucfirst($measure->type);
            $source = str_replace("Open Book Publishers", "OBP", $source);
            $measurement = [];
            foreach ($this->years_active as $y => $month) {
                if ($year === null) {
                    $measurement[$y] = 0;
                } elseif ($year === $y) {
                    foreach ($month as $m => $tmp) {
                        $measurement[$m] = 0;
                    }
                }
            }
            foreach ($measure->data as $date) {
                $measurement[$date->$key] = (int)$date->value;
            }
            $stats[$source] = $measurement;
        }
        return $stats;
    }

    private function getMeasurement($type, $measure, $alias, $year, $month = null)
    {
        switch ($type) {
            case "sales":
                if ($month === null) {
                    return $this->getSalesFormatYear($alias, $year);
                } else {
                    return $this->getSalesFormatMonth($alias, $year, $month);
                }
        }
    }

    private function getSales($type, $year = null)
    {
        $stats = [];
        $measures = $this->getFormats();

        foreach ($measures as $measure => $alias) {
            if (!isset($stats[$alias])) {
                $stats[$alias] = [];
            }

            foreach ($this->years_active as $y => $months) {
                if ($year === null) {
                    if (!isset($stats[$alias][$y])) {
                        $stats[$alias][$y] = 0;
                    }

                    $units = $this->getMeasurement($type, $measure,
                                                       $alias, $y);
                    $stats[$alias][$y] += $units;
                } elseif ($year === $y) {
                    foreach ($months as $m => $na) {
                        if (!isset($stats[$alias][$m])) {
                            $stats[$alias][$m] = 0;
                        }

                        $units = $this->getMeasurement($type, $measure,
                                                       $alias, $y, $m);
                        $stats[$alias][$m] += $units;
                    }
                }
            }

            // clean up
            $cleanup = true;
            foreach ($stats[$alias] as $y => $val) {
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
        foreach ($this->years_active as $y => $months) {
            if (!isset($revenue[$y])) {
                $revenue[$y] = 0.00;
            }

            $total = $this->getTotalNetRevenueInYear($y);
            $revenue[$y] += $total;
        }

        return $revenue;
    }

    /**
     * Obtain a multidimensional array in the form of
     * [(integer)Year => (float)NetRevenue].
     *
     * @return array
     */
    private function getTotalNetRevenueByQuarter($year)
    {
        $revenue = [];
        foreach ($this->quarters as $q => $na) {
            if (!isset($revenue[$q])) {
                $revenue[$q] = 0.00;
            }

            $total = $this->getTotalNetRevenueInYear($year, $q);
            $revenue[$q] += $total;
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
        foreach ($this->years_active as $y => $months) {
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
        foreach ($this->years_active as $y => $months) {
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
     * [(integer)Year => (float)Income].
     *
     * @param int $year
     * @return array
     */
    private function getNonSalesIncomeByQuarter($year)
    {
        $revenue = [];
        foreach ($this->quarters as $q => $na) {
            if (!isset($revenue[$q])) {
                $revenue[$q] = 0.00;
            }

            $total = $this->getNonSalesIncome($year, $q);
            $revenue[$q] += $total;
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
        foreach ($this->years_active as $y => $months) {
            if (!isset($revenue[$y])) {
                $revenue[$y] = 0.00;
            }

            $total = $this->getNonSalesCosts($y);
            $revenue[$y] += $total;
        }

        return $revenue;
    }

    /**
     * Obtain a multidimensional array in the form of
     * [(integer)Year => (float)Costs].
     *
     * @param int $year
     * @return array
     */
    private function getNonSalesCostsByQuarter($year)
    {
       $revenue = [];
        foreach ($this->quarters as $q => $na) {
            if (!isset($revenue[$q])) {
                $revenue[$q] = 0.00;
            }

            $total = $this->getNonSalesCosts($year, $q);
            $revenue[$q] += $total;
        }

        return $revenue;
    }

    /**
     * Obtain the total net revenue for this book in a given year.
     *
     * @param int $year
     * @return float
     */
    private function getTotalNetRevenueInYear($year, $quarter = null)
    {
        $q =  DB::table('sale')
            ->join('volume', 'sale.volume_id', '=', 'volume.volume_id')
            ->join('book', 'volume.book_id', '=', 'book.book_id')
            ->select(DB::raw('SUM(`net_revenue`) as total'))
            ->whereYear('sale_date', $year)
            ->where('doi', '=', $this->doi);
        if ($quarter) {
            $q = $q->whereRaw('QUARTER(sale_date) = ?', [$quarter]);
        }
        $result = $q->first();

        return $result->total !== null ? (float) $result->total : 0.00;

    }

    /**
     * Obtain the total net revenue for this book.
     *
     * @param int $year
     * @return float
     */
    private function getTotalNetRevenue()
    {
        $result =  DB::table('sale')
            ->join('volume', 'sale.volume_id', '=', 'volume.volume_id')
            ->join('book', 'volume.book_id', '=', 'book.book_id')
            ->select(DB::raw('SUM(`net_revenue`) as total'))
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
    private function getNonSalesIncome($year = null, $quarter = null)
    {
        return $this->getSubventions("income", $year, $quarter);

    }

    /**
     * Obtain the total extra income (not obtained from sales)
     * for this book in a given year.
     *
     * @param int $year
     * @return float
     */
    private function getNonSalesCosts($year = null, $quarter = null)
    {
        return $this->getSubventions("cost", $year, $quarter);

    }

    /**
     * Obtain the total positive or negative subventions
     * for this book in a given year.
     *
     * @param string $type
     * @param int $year
     * @return float
     */
    private function getSubventions($type, $year = null, $quarter = null)
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

        return $year === null
            ? $this->getTotalSubventions($symbol)
            : $this->getSubventionsInYear($symbol, $year, $quarter);
    }

    private function getSubventionsInYear($symbol, $year, $quarter = null)
    {
        $q = DB::table('subvention')
            ->join('book', 'book.book_id', '=', 'subvention.book_id')
            ->select(DB::raw('SUM(`subvention_value`) as total'))
            ->whereYear('subvention_date', $year)
            ->where([
                ['subvention_value', $symbol, 0],
                ['doi', '=', $this->doi]]);
        if ($quarter) {
            $q = $q->whereRaw('QUARTER(subvention_date) = ?', [$quarter]);
        }
        $result = $q->first();

        return $result->total !== null ? (float) $result->total : 0.00;
    }

    private function getTotalSubventions($symbol)
    {
        $result =  DB::table('subvention')
            ->join('book', 'book.book_id', '=', 'subvention.book_id')
            ->select(DB::raw('SUM(`subvention_value`) as total'))
            ->where([
                ['subvention_value', $symbol, 0],
                ['doi', '=', $this->doi]])
            ->first();

        return $result->total !== null ? (float) $result->total : 0.00;
    }

    public function getWorkUriStr()
    {
        return 'info:doi:' . strtolower($this->doi);
    }

    private function getMeasureUriStr($measure)
    {
        return 'measure_uri:' . strtolower($measure);
    }

    private function getEvents($filter = '', $aggregation = '', $start = '',
                               $end = '')
    {
        // see if request has been cached
        if (Cache::has("sigs-${order}")) {
            return response()->json(Cache::get("sigs-${order}"));
        }
        $request = url(config('app.api').'/events'
            . '?filter=' . $filter
            . '&aggregation=' . $aggregation
            . '&start_date=' . $start
            . '&end_date=' . $end);
        if (substr(get_headers($request)[0], 9, 3) !== "200") {
                return [];
        }
        $response = file_get_contents($request);
        $result = json_decode($response);
        return $result->count !== 0 ? $result->data : [];
    }

    /**
     * Obtain the total readership aggregated by measure for this book
     *
     * @param string $measure
     * @param int $year
     * @return array
     */
    private function getReadershipByMeasureYear($year = null)
    {
        $start_date = $year !== null ? "${year}-01-01" : "";
        $end_date = $year !== null ? "${year}-12-31" : "";
        $aggregation = $year !== null ? "measure_uri,month"
                                      : "measure_uri,year";
        $work_uri = 'work_uri:' . $this->getWorkUriStr();

        return $this->getEvents($work_uri, $aggregation,
                                $start_date, $end_date);
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
     * Obtain the total sales of this book in a given year and month
     * for a particular book format (e.g. epub, hardback, pdf)
     *
     * @param string $format
     * @param int $year
     * @param int $month
     * @return int
     */
    private function getSalesFormatMonth($format, $year, $month)
    {
        $result = DB::table('sale')
            ->join('volume','sale.volume_id', '=', 'volume.volume_id')
            ->join('book','volume.book_id', '=', 'book.book_id')
            ->select( DB::raw('SUM(`sales_units`) as sales'))
            ->whereYear('sale_date', $year)
            ->whereMonth('sale_date', $month)
            ->where([['doi', '=', $this->doi], ['format_name', '=', $format]])
            ->first();

        return $result->sales !== null ? $result->sales : 0;
    }

    /**
     * Obtain the total this books sales in a given year
     *
     * @param int $year
     * @param int|null $quarter
     * @return int
     */
    private function getTotalSalesByYear($year, $quarter = null)
    {
        $q = DB::table('sale')
            ->join('volume','sale.volume_id', '=', 'volume.volume_id')
            ->join('book','volume.book_id', '=', 'book.book_id')
            ->select( DB::raw('SUM(`sales_units`) as sales'))
            ->whereYear('sale_date', $year)
            ->where('doi', '=', $this->doi);
        if ($quarter) {
            $q = $q->whereRaw('QUARTER(sale_date) = ?', [$quarter]);
        }
        $result = $q->first();

        return $result->sales !== null ? $result->sales : 0;
    }

    /**
     * Obtain the total this books sales in a given year
     *
     * @param int $year
     * @return int
     */
    private function getTotalSales()
    {
        $result = DB::table('sale')
            ->join('volume','sale.volume_id', '=', 'volume.volume_id')
            ->join('book','volume.book_id', '=', 'book.book_id')
            ->select( DB::raw('SUM(`sales_units`) as sales'))
            ->where('doi', '=', $this->doi)
            ->first();

        return $result->sales !== null ? $result->sales : 0;
    }

    /**
     * Obtain the total this books sales in a given year
     *
     * @param int $year
     * @return int
     */
    private function getTotalSalesByYearMonth($year, $month)
    {
        $result = DB::table('sale')
            ->join('volume','sale.volume_id', '=', 'volume.volume_id')
            ->join('book','volume.book_id', '=', 'book.book_id')
            ->select( DB::raw('SUM(`sales_units`) as sales'))
            ->whereYear('sale_date', $year)
            ->whereMonth('sale_date', $month)
            ->where('doi', '=', $this->doi)
            ->first();

        return $result->sales !== null ? $result->sales : 0;
    }
}
