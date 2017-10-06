<?php

namespace App\Http\Controllers;

use App\Book;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class BooksController extends Controller
{
    
    private $table_data = [
        "readership" => [
            "title" => "Online Readership",
            "column" => "Platform",
            "totals_col" => "Total Online Reader",
            "data" => []
         ],
         "downloads" => [
            "title" => "Free Downloads",
            "column" => "Platform",
            "totals_col" => "Total eBook Downloads",
            "data" => []
         ],
         "sales" => [
            "title" => "Number of Sales",
            "column" => "Format",
            "totals_col" => "Total Sales",
            "data" => []
         ],
         "royalties" => [
            "title" => "Revenue and Royalties Summary (GBP)",
            "column" => "",
            "totals_col" => "",
            "data" => []
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    /**
     * Render a view with readership graphs
     *
     * @param type $book_id
     * @return type
     */
    public function readershipGraphs($book_id)
    {
        $book = Book::findOrFail($book_id);
        $book->loadCountryReadership();
        $book->loadContinentReadership();
        $this->graph_data['countries']['data'] = $book->countries;
        $this->graph_data['countries']['total']
            = $this->graph_data['continents']['total']
            = $book->total_country_readership;
        $this->graph_data['continents']['data'] = $book->continents;
        $data = $this->graph_data;
        $colours = $this->colours;
        
        return view('books.graphs-headers',
            compact('book', 'data', 'colours'));
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
        $map_url = "https://data.openbookpublishers.com/static/map/book-countries.html?doi=" . $book->doi;
        
        return view('books.map', compact('book', 'map_url'));
    }

    /**
     * Generate the report in HTML
     *
     * @param int $book_id
     * @return Illuminate\Support\Facades\View
     */
    public function fullReport($book_id)
    {
        $book = Book::findOrFail($book_id);
        return View::make('books.report', ['book' => $book]);
    }

    /**
     * Renders the report in PDF and returns it as a string
     *
     * @param int $book_id
     * @return string
     */
    public function fullReportPdf($book_id)
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml($this->fullReport($book_id)->render());
        $dompdf->render();
        return $dompdf->output();
    }

    /**
     * Generates the report and outputs it as a PDF
     *
     * @param int $book_id
     * @return Response
     */
    public function downloadFullReport($book_id)
    {
        $book = Book::findOrFail($book_id);
        $filename = $book->book_id . '.pdf';
        
        return new Response($this->fullReportPdf($book_id), 200, [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'application/pdf',
        ]);   
    }
}
