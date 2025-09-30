<?php 
namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private string $inviteUrl,
        private string $teamName
    ) {}

    public function build()
    {
        return $this->subject("You're invited to join {$this->teamName}")
                    ->view('emails.team_invitation')
                    ->with([
                        'inviteUrl' => $this->inviteUrl,
                        'teamName'  => $this->teamName,
                    ]);
    }
}
