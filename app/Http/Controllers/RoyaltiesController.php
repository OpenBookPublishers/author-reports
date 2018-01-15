<?php

namespace App\Http\Controllers;

use App\Author;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class RoyaltiesController extends Controller
{

    private $table_data = [
        "title" => "Revenue and Royalties Summary (GBP)",
        "column" => "",
        "totals_col" => "",
        "data" => [],
        "year_totals" => []
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Render an interface to manage royalty agreements
     *
     * @return Response
     */
    public function index()
    {
        $authors = Author::allWithRoyalties();
        foreach ($authors as $author) {
            $author->loadRoyalties();
        }

        return view('royalties.index', compact('authors'));
    }

    private function getTableData($book, $agreement)
    {
        $data = $this->table_data;
        $data['data'] =
            $book->calculateRoyaltiesInAgreement($agreement);
        return $data;
    }

    /**
     * Generate the report in HTML
     *
     * @param int $author_id
     * @return Illuminate\Support\Facades\View
     */
    public function royaltyReport($author_id)
    {
        $author = Author::findOrFail($author_id);
        if (!$author->receivesRoyalties()) {
            Session::flash('info',
                'This author does not have any royalty agreement.');
            return back();
        }

        $year = null;
        $is_pdf = true;
        $is_public = false;
        $books = [];
        foreach ($author->royaltyRecipients as $recipient) {
            $agreement = $recipient->royaltyAgreement;
            $book = $agreement->book;
            $book->years_active = $book->getYearsActive();
            $book->data = $this->getTableData($book, $agreement);
            $books[] = $book;
        }
        
        return View::make('royalties.report-html',
            compact('books', 'year', 'is_pdf', 'is_public'));
    }

    /**
     * Renders the report in PDF and returns it as a string
     *
     * @param int $author_id
     * @return string
     */
    private function royaltyReportPdf($author_id)
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml($this->royaltyReport($author_id)->render());
        $dompdf->render();
        return $dompdf->output();
    }

    /**
     * Generates the report and outputs it as a PDF
     *
     * @param int $author_id
     * @return Response
     */
    public function downloadRoyaltyReport($author_id)
    {
        $author = Author::findOrFail($author_id);
        $title = "Royalties_Report";
        $filename = $title . "-" . $author->sanitisedName() . ".pdf";

        return new Response($this->royaltyReportPdf($author_id), 200, [
           'Content-Description' => 'File Transfer',
           'Content-Disposition' => 'attachment; filename="'.$filename.'"',
           'Content-Transfer-Encoding' => 'binary',
           'Content-Type' => 'application/pdf',
        ]);   
    }
}
