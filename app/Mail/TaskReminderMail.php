<?php
namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskReminderMail extends Mailable {
    use Queueable, SerializesModels;

    public function __construct(
        public Task $task,
        public User $user
    ) {}

    public function envelope(): Envelope {
        $mins = $this->task->reminder_minutes;
        $label = $mins >= 60 ? ($mins / 60) . ' hour(s)' : $mins . ' min(s)';
        return new Envelope(
            subject: '⏰ Reminder: "' . $this->task->title . '" starts in ' . $label . '!',
        );
    }

    public function content(): Content {
        return new Content(view: 'emails.task-reminder');
    }
}