<?php
namespace App\Http\Controllers;

use App\Models\RosterNotification;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller {

    // GET /api/tasks — get all tasks for this household
    public function index(Request $request) {
        $user = $request->user();

        if (!$user->household_id) {
            return response()->json([]);
        }

        $tasks = Task::where('household_id', $user->household_id)
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('start_date')
            ->get();

        return response()->json($tasks);
    }

    // POST /api/tasks — create a new task
    public function store(Request $request) {
        $request->validate([
            'title'       => 'required|string',
            'user_id'     => 'required|exists:users,id',
            'start_date'  => 'required|date',
            'recurrence'  => 'required|in:once,weekly,fortnightly,monthly',
            'category'    => 'nullable|string',
            'start_time'  => 'nullable',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();

        $task = Task::create([
            'household_id' => $user->household_id,
            'created_by'   => $user->id,
            'user_id'      => $request->user_id,
            'title'        => $request->title,
            'description'  => $request->description,
            'category'     => $request->category ?? 'general',
            'recurrence'   => $request->recurrence,
            'start_date'   => $request->start_date,
            'start_time'   => $request->start_time,
            'status'       => 'pending',
        ]);

        // tell everyone else in the household
        $this->notifyOthers(
            $user, $task, 'created',
            $user->name . ' added "' . $task->title . '"'
        );

        return response()->json($task->load(['assignedTo', 'createdBy']), 201);
    }

    // PUT /api/tasks/{task} — update a task
    public function update(Request $request, Task $task) {
        // make sure task belongs to this household
        if ($task->household_id !== $request->user()->household_id) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $task->update($request->only([
            'title', 'description', 'user_id', 'category',
            'recurrence', 'start_date', 'start_time', 'status'
        ]));

        $user = $request->user();
        $this->notifyOthers(
            $user, $task, 'updated',
            $user->name . ' updated "' . $task->title . '"'
        );

        return response()->json($task->load(['assignedTo', 'createdBy']));
    }

    // DELETE /api/tasks/{task}
    public function destroy(Request $request, Task $task) {
        if ($task->household_id !== $request->user()->household_id) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $user  = $request->user();
        $title = $task->title;

        $this->notifyOthers(
            $user, $task, 'deleted',
            $user->name . ' deleted "' . $title . '"'
        );

        $task->delete();

        return response()->json(['message' => 'Deleted']);
    }

    // send notification to everyone except current user
    private function notifyOthers($currentUser, $task, $type, $message) {
        foreach ($currentUser->household->members as $member) {
            if ($member->id === $currentUser->id) continue;
            RosterNotification::create([
                'user_id' => $member->id,
                'task_id' => $task->id,
                'type'    => $type,
                'message' => $message,
                'read'    => false,
            ]);
        }
    }
}