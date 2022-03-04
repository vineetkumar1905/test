<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
use URL;
use Illuminate\Support\Facades\Storage;  
use Illuminate\Contracts\Routing\ResponseFactory;
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to pdf generation',
            'date' => date('m/d/Y')
        ];
          
        $pdf = PDF::loadView('myPDF', $data);       
        return $pdf->download('download.pdf');

    }
}
