<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permintaan;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function printRequest(Permintaan $permintaan)
    {
        $permintaan->load(['cabang', 'user', 'details.produk']);
        $pdf = Pdf::loadView('documents.permintaan', compact('permintaan'))
                  ->setPaper('a5', 'landscape');
        
        return $pdf->stream('Surat_Permintaan_' . $permintaan->no_request . '.pdf');
    }

    public function printDelivery(Permintaan $permintaan)
    {
        if (!in_array($permintaan->status, ['shipped', 'received', 'received_complete', 'received_partial'])) {
            return redirect()->back()->with('error', 'Surat Jalan belum dapat dicetak.');
        }

        $permintaan->load(['cabang', 'user', 'details.produk']);
        $pdf = Pdf::loadView('documents.shipping', compact('permintaan'))
                  ->setPaper('a5', 'landscape');
        
        return $pdf->stream('Surat_Jalan_' . $permintaan->no_request . '.pdf');
    }

    public function printReceipt(Permintaan $permintaan)
    {
        if (!in_array($permintaan->status, ['received', 'received_complete', 'received_partial'])) {
            return redirect()->back()->with('error', 'Surat Terima belum dapat dicetak.');
        }

        $permintaan->load(['cabang', 'user', 'details.produk']);
        $pdf = Pdf::loadView('documents.receipt', compact('permintaan'))
                  ->setPaper('a5', 'landscape');
        
        return $pdf->stream('Surat_Terima_' . $permintaan->no_request . '.pdf');
    }
}
