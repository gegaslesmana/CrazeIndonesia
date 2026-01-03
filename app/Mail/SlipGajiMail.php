<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $pdfPath;
    public $filename;
    public $periode;

    public function __construct($nama, $pdfPath, $filename, $periode)
    {
        $this->nama = $nama;
        $this->pdfPath = $pdfPath;
        $this->filename = $filename;
        $this->periode = $periode;
    }

    public function build()
{
    return $this->view('emails.slip_gaji')
                ->with([
                    'nama' => $this->nama,
                    'periode' => $this->periode
                ])
                ->attach($this->pdfPath, [
                    'as' => $this->filename . '.pdf',
                    'mime' => 'application/pdf',
                ]);
}
}