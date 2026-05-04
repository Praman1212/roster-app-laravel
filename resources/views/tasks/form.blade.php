@extends('layouts.app')

@section('content')
<div class="page">
<div style="max-width:560px; margin: 0 auto;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <h2 style="font-size:22px; color:#4f46e5;">
            {{ $task ? '✏️ Edit Task' : '✨ New Task' }}
        </h2>
        <a href="/tasks" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    {{-- If editing use PUT, if creating use POST --}}
    <form method="POST" action="{{ $task ? '/tasks/' . $task->id : '/tasks' }}"
          style="background:white; border-radius:16px; padding:28px; box-shadow:0 2px 20px rgba(0,0,0,0.07);">
        @csrf
        @if($task) @method('PUT') @endif

        {{-- Title --}}
        <div class="form-group">
            <label>Task Title *</label>
            <input name="title" value="{{ old('title', $task?->title) }}" placeholder="e.g. Buy groceries" required />
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label>Description (optional)</label>
            <textarea name="description" rows="2" placeholder="Any extra notes...">{{ old('description', $task?->description) }}</textarea>
        </div>

        {{-- Assign + Category --}}
        <div class="form-row">
            <div class="form-group">
                <label>Assign To *</label>
                <select name="user_id" required>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ (old('user_id', $task?->user_id) == $member->id) ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    @foreach(['general','cooking','cleaning','shopping','work','health','school'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $task?->category) === $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Date + Time --}}
        <div class="form-row">
            <div class="form-group">
                <label>Date *</label>
                <input type="date" name="start_date" value="{{ old('start_date', $task?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required />
            </div>
            <div class="form-group">
                <label>Time (optional)</label>
                <input type="time" name="start_time" value="{{ old('start_time', $task?->start_time ? substr($task->start_time, 0, 5) : '') }}" />
            </div>
        </div>

        {{-- Recurrence + Status --}}
        <div class="form-row">
            <div class="form-group">
                <label>Repeats</label>
                <select name="recurrence">
                    @foreach(['once','weekly','fortnightly','monthly'] as $r)
                        <option value="{{ $r }}" {{ old('recurrence', $task?->recurrence) === $r ? 'selected' : '' }}>
                            {{ ucfirst($r) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($task)
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="pending"     {{ $task->status === 'pending'     ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>🔵 In Progress</option>
                    <option value="done"        {{ $task->status === 'done'        ? 'selected' : '' }}>✅ Done</option>
                </select>
            </div>
            @endif
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:10px 14px; border-radius:8px; margin-bottom:14px; font-size:14px;">
                @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
            </div>
        @endif

        {{-- Submit --}}
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px;">
            <a href="/tasks" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">💾 Save Task</button>
        </div>
    </form>

</div>
</div>
@endsection