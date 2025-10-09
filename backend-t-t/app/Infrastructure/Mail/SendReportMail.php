<?php 
namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class SendReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private array $categoryWiseReports,private array $dateWiseReports, $startDate, $endDate)
    {
    }

    public function build()
    {
     
        return $this->subject('Task Report Summary')
            ->view('emails.report');

    }
}
