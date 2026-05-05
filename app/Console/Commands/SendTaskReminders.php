<?php
namespace App\Console\Commands;

use App\Mail\TaskReminderMail;
use App\Models\RosterNotification;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTaskReminders extends Command {

    // this is the command name you type in terminal
    protected $signature   = 'roster:reminders';
    protected $description = 'Send email reminders for upcoming tasks';

    public function handle() {
        $now = Carbon::now();
        $this->info('Checking reminders at: ' . $now->format('Y-m-d H:i'));

        // get tasks that have reminder set and are not done
        $tasks = Task::whereNotNull('reminder_minutes')
            ->whereNotNull('start_time')
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['assignedTo'])
            ->get();

        $this->info('Found ' . $tasks->count() . ' task(s) with reminders set.');

        $sent = 0;

        foreach ($tasks as $task) {
            // build full task datetime e.g. "2026-05-05 11:00:00"
            $taskDateTime = Carbon::parse($task->start_date . ' ' . $task->start_time);

            // when should reminder fire?
            // e.g. task at 11:00, reminder 30 mins = fire at 10:30
            $reminderTime = $taskDateTime->copy()->subMinutes($task->reminder_minutes);

            // check if now matches reminder time within 1 min window
            $diff = abs($now->diffInMinutes($reminderTime, false));

            $this->info(
                'Task: "'   . $task->title . '"' .
                ' | Fires: ' . $reminderTime->format('Y-m-d H:i') .
                ' | Now: '   . $now->format('H:i') .
                ' | Diff: '  . $diff . ' min(s)'
            );

            if ($diff > 1) {
                $this->info('   → Not time yet, skipping.');
                continue;
            }

            $user = $task->assignedTo;
            if (!$user || !$user->email) {
                $this->info('   → No user/email found, skipping.');
                continue;
            }

            // send email
            Mail::to($user->email)->send(new TaskReminderMail($task, $user));

            // save as in-app notification
            RosterNotification::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'type'    => 'reminder',
                'message' => '⏰ Reminder: "' . $task->title . '" starts in ' . $task->reminder_minutes . ' mins!',
                'read'    => false,
            ]);

            $sent++;
            $this->info('   → ✅ Reminder sent to: ' . $user->email);
        }

        $this->info('Done! ' . $sent . ' reminder(s) sent.');
    }
}