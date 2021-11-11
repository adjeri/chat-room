<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Models\User;
use Illuminate\Http\Request;

class VideoChatController extends Controller
{
    public function index(Request $request)
    {   // ビデオチャットページ

        $user = $request->user();
        $others = User::where('id', '!=', $user->id)->pluck('name', 'id');
        return view('video-chat')->with([
            'user' => collect($request->user()->only(['id', 'name'])),
            'others' => $others
        ]);
    }

    public function auth(Request $request)
    {    // Pusherの認証

        $user = $request->user();
        $socket_id = $request->socket_id;
        $channel_name = $request->channel_name;
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => true
            ]
        );
        return response(
            $pusher->presence_auth($channel_name, $socket_id, $user->id)
        );
    }
}
