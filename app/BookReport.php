<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookReport extends Model
{
   public $author;
    public $date;
    public $title;
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
            "data" => []
        ],
        "continents" => [
            "title" => "Unique Visits to Online Readers by Continent <br>"
                       . "(when available)",
            "column" => "Continent",
            "info" => "",
            "data" => []
        ]
    ];

    public function __construct($doi)
    {
        $this->book = new Book($doi);
        if ($this->book->publication_date === null) {
            die("Book has not been published.");
        }
    }

    public function render()
    {
        $this->book->loadData();

        $this->table_data['readership']['data'] = $this->book->readership;
        $this->table_data['downloads']['data'] = $this->book->downloads;
        $this->table_data['sales']['data'] = $this->book->sales;
        $this->table_data['royalties']['data'] = $this->book->royalties;

        $this->graph_data['countries']['data'] = $this->book->countries;
        $this->graph_data['continents']['data'] = $this->book->continents;
        $this->graph_data['countries']['info'] = "Total number of countries: "
                                               . $this->book->total_countries;

        $tables = $this->generateTables();
        $graphs = $this->generateGraphs();

        ob_start();
        include('view/authorreport.php');
        $html = ob_get_clean();

        echo $html;
    }

    private function generateTables()
    {
        $tables = "";
        foreach ($this->table_data as $table) {
            $title      = $table['title'];
            $column     = $table['column'];
            $data       = $table['data'];
            $totals_col = $table['totals_col'];

            ob_start();
            include('view/table.php');
            $tables .= ob_get_clean();
        }

        return $tables;
    }

    private function generateGraphs()
    {
        $graphs = "";
        foreach ($this->graph_data as $graph) {
            $title  = $graph['title'];
            $column = $graph['column'];
            $info   = $graph['info'];
            $data   = $graph['data'];

            ob_start();
            include('view/graph.php');
            $graphs .= ob_get_clean();
        }

        return $graphs;
    }
}
