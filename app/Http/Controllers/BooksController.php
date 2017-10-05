<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

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

    public function readershipMap($book_id)
    {
        $book = Book::findOrFail($book_id);
        $map_url = "https://data.openbookpublishers.com/static/map/book-countries.html?doi=" . $book->doi;
        
        return view('books.map', compact('book', 'map_url'));
    }
}
