<?php 
namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class SendReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private array $categoryWiseReports,private array $dateWiseReports, $startDate, $endDate, $pdf)
    {
    }

    public function build()
    {
     
        return $this->subject('Your task report is due')
            ->view('emails.report');

    }
}
