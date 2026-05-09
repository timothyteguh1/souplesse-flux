<?php

namespace App\Traits\Livewire;

trait HasCetakBrowser
{
    public function cetakPdf($pdf)
    {
        $pdfBase64 = _convert_pdf_output_to_base64($pdf->output());
        $this->dispatch('cetakPdf', base64: $pdfBase64);
    }

    public function cetakRawHtml($rawHtml)
    {
        $this->dispatch('cetakRawHtml', rawHtml: $rawHtml);
    }
}
