<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller {

    // GET /api/notifications
    public function index(Request $request) {
        $notifications = $request->user()
            ->rosterNotifications()
            ->latest()
            ->take(30)
            ->get();

        return response()->json($notifications);
    }

    // PATCH /api/notifications/{id}/read
    public function markRead(Request $request, $id) {
        $notif = $request->user()->rosterNotifications()->findOrFail($id);
        $notif->update(['read' => true]);
        return response()->json($notif);
    }

    // POST /api/notifications/read-all
    public function markAllRead(Request $request) {
        $request->user()->rosterNotifications()->update(['read' => true]);
        return response()->json(['message' => 'All read']);
    }
}