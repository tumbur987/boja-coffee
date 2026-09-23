<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('transaction.table')
            ->latest()
            ->limit(20)
            ->get();

        $unreadCount = Notification::where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markRead(Request $request)
    {
        $ids = $request->input('ids', []);

        if ($ids) {
            Notification::whereIn('id', $ids)->update(['is_read' => true]);
        } else {
            Notification::where('is_read', false)->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $count = Notification::where('is_read', false)->count();
        return response()->json(['unread_count' => $count]);
    }
}
