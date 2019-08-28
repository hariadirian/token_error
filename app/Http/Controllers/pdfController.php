<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\M_Cart_Product_HD;
use Illuminate\Http\Request;
use Auth;

class pdfController extends Controller
{
    public function exportPDF()
    {
        $pdf = PDF::loadView('PDF.attachment_email');
        return $pdf->stream();
    }

    public function exportPDF2()
    {
        $pdf = PDF::loadView('PDF.paid');
        return $pdf->stream();
    }

    public function unpaid()
    {
        $data['unpaid'] = M_Cart_Product_HD::where('state', 'Y')
            ->where('is_active', 'Y')
            ->where('is_done', 'N')
            ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
            ->with(['toCartProductBd' => function($query){
                $query->where('is_active', 'Y');
                $query->where('state', 'Y');
                $query->with(['toTicketImgHd' => function($query1) {
                    $query1->where('tmii_ms_ticket_img_hd.state', 'Y');
                    $query1->with(['toTicketImgDt' => function($query2){
                        $query2->where('state', 'Y');
                        $query2->where('img_type', 'BOX');
                    }]);
                }]);
        }])->get();
        $pdf = PDF::loadView('PDF.unpaid', $data)->download('unpaid.pdf');
        return redirect()->route('mail.unpaid');
    }


    public function invoice()
    {
        $data['unpaid'] = M_Cart_Product_HD::where('state', 'Y')
            ->where('is_active', 'Y')
            ->where('is_done', 'N')
            ->where('id_us_frontend_hd', Auth::user()->id_us_frontend_hd)
            ->with(['toCartProductBd' => function($query){
                $query->where('is_active', 'Y');
                $query->where('state', 'Y');
                $query->with(['toTicketImgHd' => function($query1) {
                    $query1->where('tmii_ms_ticket_img_hd.state', 'Y');
                    $query1->with(['toTicketImgDt' => function($query2){
                        $query2->where('state', 'Y');
                        $query2->where('img_type', 'BOX');
                    }]);
                }]);
            }])->get();
        $pdf = PDF::loadView('PDF.invoice', $data)->download('invoice.pdf');
//        return redirect()->route('mail.unpaid');
    }





}
