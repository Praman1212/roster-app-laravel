<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f4ff; margin: 0; padding: 20px; }
    .box { background: white; border-radius: 16px; padding: 32px; max-width: 480px; margin: 0 auto; }
    .logo { font-size: 22px; font-weight: 800; color: #4f46e5; margin-bottom: 20px; }
    .greeting { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
    .msg { font-size: 14px; color: #555; margin-bottom: 20px; line-height: 1.6; }
    .countdown { background: #4f46e5; color: white; border-radius: 12px; padding: 14px 20px; text-align: center; font-size: 22px; font-weight: 800; margin-bottom: 20px; }
    .card { background: #f5f3ff; border-left: 4px solid #4f46e5; border-radius: 10px; padding: 16px; margin-bottom: 20px; }
    .task-title { font-size: 18px; font-weight: 700; color: #4f46e5; margin-bottom: 10px; }
    .row { font-size: 13px; color: #555; margin-bottom: 5px; }
    .row b { color: #111; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 6px; }
    .pending     { background: #fef3c7; color: #92400e; }
    .in_progress { background: #dbeafe; color: #1e40af; }
    .btn { display: block; background: #4f46e5; color: white; text-decoration: none; padding: 14px; border-radius: 10px; font-size: 15px; font-weight: 700; text-align: center; margin-bottom: 20px; }
    .footer { font-size: 11px; color: #aaa; text-align: center; }
  </style>
</head>
<body>
<div class="box">
  <div class="logo">🏠 FamRoster</div>
  <div class="greeting">Hey {{ $user->name }}! 👋</div>
  <div class="msg">You have a task coming up and it's still not done!</div>

  @php
    $mins  = $task->reminder_minutes;
    $label = $mins >= 60 ? ($mins / 60) . ' hour(s)' : $mins . ' minute(s)';
  @endphp
  <div class="countdown">⏰ Starts in {{ $label }}!</div>

  <div class="card">
    <div class="task-title">{{ $task->title }}</div>
    @if($task->description)
      <div class="row">📝 <b>Notes:</b> {{ $task->description }}</div>
    @endif
    <div class="row">📅 <b>Date:</b> {{ \Carbon\Carbon::parse($task->start_date)->format('D, d M Y') }}</div>
    @if($task->start_time)
      <div class="row">🕐 <b>Time:</b> {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }}</div>
    @endif
    <div class="row">📂 <b>Category:</b> {{ ucfirst($task->category) }}</div>
    <div class="row">🔁 <b>Repeats:</b>  {{ ucfirst($task->recurrence) }}</div>
    <div class="badge {{ $task->status }}">
      @if($task->status === 'pending') ⏳ Pending
      @else 🔵 In Progress
      @endif
    </div>
  </div>

  <a href="{{ env('FRONTEND_URL', 'http://localhost:5173') }}" class="btn">
    ✅ Open FamRoster &amp; Mark as Done
  </a>

  <div class="footer">FamRoster automated reminder</div>
</div>
</body>
</html>