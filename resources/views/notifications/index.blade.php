@extends('layouts.app')

@section('content')
<div class="page">
<div style="max-width:600px; margin:0 auto;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <h2 style="font-size:22px; color:#4f46e5;">🔔 Notifications</h2>
        <a href="/tasks" class="btn btn-secondary btn-sm">← Back to Tasks</a>
    </div>

    <div style="background:white; border-radius:16px; padding:8px 22px; box-shadow:0 2px 20px rgba(0,0,0,0.07);">

        @if($notifications->isEmpty())
            <div class="empty">
                <div class="empty-icon">🎉</div>
                <p>All caught up! No notifications.</p>
            </div>
        @endif

        @php
            $icons = ['created'=>'✅', 'updated'=>'✏️', 'deleted'=>'🗑️', 'reminder'=>'⏰'];
        @endphp

        @foreach($notifications as $n)
        <div class="notif-item {{ $n->read ? '' : 'unread' }}">
            <span style="font-size:18px; flex-shrink:0;">{{ $icons[$n->type] ?? '🔔' }}</span>
            <div>
                <div class="notif-msg">{{ $n->message }}</div>
                <div class="notif-time">{{ $n->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach

    </div>

</div>
</div>
@endsection