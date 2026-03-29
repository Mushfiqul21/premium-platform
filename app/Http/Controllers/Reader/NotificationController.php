<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('reader.dashboard');

        return redirect($url);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
