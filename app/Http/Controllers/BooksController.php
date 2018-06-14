<?php

namespace App\Http\Controllers;

use App\Book;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class BooksController extends Controller
{
    
    private $table_data = [
        "readership" => [
            "title" => "Online Readership",
            "column" => "Platform",
            "totals_col" => "Total Online Reader",
            "data" => [],
            "year_totals" => [],
            "global_total" => 0
         ],
         "downloads" => [
            "title" => "Free Downloads",
            "column" => "Platform",
            "totals_col" => "Total eBook Downloads",
            "data" => [],
            "year_totals" => [],
            "global_total" => 0
         ],
         "sales" => [
            "title" => "Number of Sales",
            "column" => "Format",
            "totals_col" => "Total Sales",
            "data" => [],
            "year_totals" => [],
            "global_total" => 0
         ],
         "royalties" => [
            "title" => "Revenue and Royalties Summary (GBP)",
            "column" => "",
            "totals_col" => "",
            "data" => [],
            "year_totals" => [],
            "global_total" => 0
         ]
    ];
    private $graph_data = [
        "countries" => [
            "title" => "Unique Visits to Online Readers by Country <br>"
                       . "(when available)",
            "column" => "Country",
            "info" => "",
            "data" => [],
            "total" => 0
        ],
        "continents" => [
            "title" => "Unique Visits to Online Readers by Continent <br>"
                       . "(when available)",
            "column" => "Continent",
            "info" => "",
            "data" => [],
            "total" => 0
        ]
    ];
    private $colours = [
       "4D4D4D", // (gray)
       "F2B705", // (yellow)
       "5DA5DA", // (blue)
       "B2912F", // (brown)
       "B276B2", // (purple)
       "FAA43A", // (orange)
       "F17CB0", // (pink)
       "FAA43A", // (orange)
       "F15854", // (red)
       "60BD68", // (green)
       "0092B9", // (blue)       
    ];

    /**
     * Render an interface to manage books
     *
     * @return Response
     */
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function publicReport($doi_prefix, $doi_suffix, $year = null)
    {
        $doi = $doi_prefix . "/" . $doi_suffix;
        $book = Book::where('doi', '=', $doi)->firstOrFail();

        if (!$book->isPublished()) {
            Session::flash('info', $book->getNotPublishedMessage());
            return back();
        }

        $book->loadCountryReadership();
        $book->loadContinentReadership();
        $this->graph_data['countries']['data'] = $book->countries;
        $this->graph_data['countries']['total']
            = $this->graph_data['continents']['total']
            = $book->total_country_readership;
        $this->graph_data['continents']['data'] = $book->continents;
        $graph_data = $this->graph_data;
        $colours = $this->colours;

        $year = $year !== null ? (int) $year : null;
        $data = $this->getTableData($book, $year);
        $map_url = "https://data.openbookpublishers.com/static/map/book-countries.html?doi=" . $book->doi;
        $is_pdf = false;
        $is_public = true;

        return view('books.public-report-headers',
            compact('book', 'data', 'year', 'is_pdf', 'is_public',
                    'map_url', 'graph_data', 'colours'));
    }

    /**
     * Load data to populate report tables
     *
     * @param Book $book
     */
    private function getTableData($book, $year = null)
    {
        $data = [];
        $book->loadAllData($year);
        $this->table_data['readership']['data'] = $book->readership;
        $data['readership'] = $this->table_data['readership'];
        $this->table_data['downloads']['data'] = $book->downloads;
        $data['downloads'] = $this->table_data['downloads'];
        
        // include sales if they are public or if user has access to them
        if ($book->areSalesPublic() || (Auth::user() !== null
            && Auth::user()->hasAccessToSalesOfBook($book->book_id))) {
            $this->table_data['sales']['data'] = $book->sales;
            $data['sales'] = $this->table_data['sales'];
        }

        // include royalties if user has access
        if (Auth::user() !== null
            && Auth::user()->isAdmin()
            && $book->hasRoyalty()) {
            foreach ($book->royaltyAgreements as $key => $agreement) {
                if (Auth::user()->isAdmin()
                    || Auth::user()->author->hasAgreement(
                        $agreement->royalty_agreement_id)) {
                    $data['royalties' . $key] = $this->table_data['royalties'];
                    $data['royalties' . $key]['data'] =
                        $book->calculateRoyaltiesInAgreement($agreement, $year);

                    // Admins will see all agreements, let's differentiate
                    if (Auth::user()->isAdmin()) {
                        $data['royalties' . $key]['title'] .= " - "
                            . $agreement->royaltyRecipient->recipient_name;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Render a view with readership graphs
     *
     * @param type $book_id
     * @return Response
     */
    public function readershipGraphs($book_id)
    {
        $book = Book::findOrFail($book_id);
        if (!$book->isPublished()) {
            Session::flash('info', $book->getNotPublishedMessage());
            return back();
        }        

        $book->loadCountryReadership();
        $book->loadContinentReadership();
        $this->graph_data['countries']['data'] = $book->countries;
        $this->graph_data['countries']['total']
            = $this->graph_data['continents']['total']
            = $book->total_country_readership;
        $this->graph_data['continents']['data'] = $book->continents;
        $graph_data = $this->graph_data;
        $colours = $this->colours;
        
        return view('books.graphs-headers',
            compact('book', 'graph_data', 'colours'));
    }

    /**
     * Render a view with an iframe to the readership map
     *
     * @param int $book_id
     * @return Response
     */
    public function readershipMap($book_id)
    {
        $book = Book::findOrFail($book_id);
        if (!$book->isPublished()) {
            Session::flash('info', $book->getNotPublishedMessage());
            return back();
        }

        $map_url = "https://data.openbookpublishers.com/static/map/book-countries.html?doi=" . $book->doi;
        
        return view('books.map-headers', compact('book', 'map_url'));
    }

    /**
     * Generate the report in HTML
     *
     * @param int $book_id
     * @param String $year to be converted to integer
     * @return Illuminate\Support\Facades\View
     */
    public function fullReportHtml($book_id, $year = null)
    {
        $book = Book::findOrFail($book_id);
        if (!$book->isPublished()) {
            Session::flash('info', $book->getNotPublishedMessage());
            return back();
        }
        $year = $year !== null ? (int) $year : null;
        $data = $this->getTableData($book, $year);
        $is_pdf = false;
        $is_public = false;

        return view('books.report-headers',
            compact('book', 'data', 'year', 'is_pdf', 'is_public'));
    }

    /**
     * Generate the report in HTML
     *
     * @param int $book_id
     * @param int $year
     * @return Illuminate\Support\Facades\View
     */
    public function fullReport($book_id, $year = null)
    {
        $book = Book::findOrFail($book_id);
        if (!$book->isPublished()) {
            Session::flash('info', $book->getNotPublishedMessage());
            return back();
        }
        $year = $year !== null ? (int) $year : null;
        $data = $this->getTableData($book, $year);
        $is_pdf = true;
        $is_public = false;
        
        return View::make('books.report-html',
            compact('book', 'data', 'year', 'is_pdf', 'is_public'));
    }

    /**
     * Renders the report in PDF and returns it as a string
     *
     * @param int $book_id
     * @param int $year
     * @return string
     */
    private function fullReportPdf($book_id, $year = null)
    {
        $dompdf = new Dompdf;
        //$dompdf->set_base_path();
        $year = $year !== null ? (int) $year : null;
        $dompdf->loadHtml($this->fullReport($book_id, $year)->render());
        $dompdf->render();
        return $dompdf->output();
    }

    /**
     * Generates the report and outputs it as a PDF
     *
     * @param int $book_id
     * @param int $year
     * @return Response
     */
    public function downloadFullReport($book_id, $year = null)
    {
        $book = Book::findOrFail($book_id);
        if (!$book->isPublished()) {
            Session::flash('info', $book->getNotPublishedMessage());
            return back();
        }

        $name = "Metrics_Report";
        $title = $year !== null ? $year . "_" . $name : $name;
        $filename = $title . "-" . $book->sanitisedTitle() . ".pdf";
        
        return new Response($this->fullReportPdf($book_id, $year), 200, [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'application/pdf',
        ]);   
    }
}
