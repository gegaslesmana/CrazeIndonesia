<?php

namespace App\Jobs;

use App\Mail\SlipGajiMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSlipGajiEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $nama;
    public $pdfPath;    // path PDF, bukan konten
    public $filename;
    public $periode;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param string $nama
     * @param string $pdfPath Path ke storage, misal "public/slips/SlipGaji_123.pdf"
     * @param string $filename Nama file yang akan dikirim
     * @param string $periode Periode slip gaji
     */
    public function __construct(string $email, string $nama, string $pdfPath, string $filename, string $periode)
    {
        $this->email = $email;
        $this->nama = $nama;
        $this->pdfPath = $pdfPath;
        $this->filename = $filename;
        $this->periode = $periode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // Ambil full path file dari storage
        $fullPath = storage_path("app/{$this->pdfPath}");

        if (!file_exists($fullPath)) {
            // Jika file PDF tidak ada, log error dan hentikan
            \Log::error("File PDF tidak ditemukan: {$fullPath}");
            return;
        }

        // Kirim email
        Mail::to($this->email)->send(
            new SlipGajiMail(
                $fullPath,
                $this->filename,
                $this->periode,
                $this->nama // bisa diteruskan ke Mailable jika mau
            )
        );
    }
}
