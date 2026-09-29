<?php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Open/create a conversation between buyer and seller
    public function show(Order $order, User $seller)
    {
        $user = Auth::user();
        if ($user->id !== $order->user_id && $user->id !== $seller->id) {
            abort(403);
        }

        $conversation = Conversation::firstOrCreate([
            'order_id'  => $order->id,
            'buyer_id'  => $order->user_id,
            'seller_id' => $seller->id,
        ]);

        $messages = $conversation->messages()->with('sender')->oldest()->get();

        return view('chat.show', compact('conversation', 'messages', 'order', 'seller'));
    }

    // Open conversation from product page (no order required — for old/used products)
    public function direct(User $seller)
    {
        $user = Auth::user();
        if ($user->id === $seller->id) {
            return back()->with('error', 'You cannot message yourself.');
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id'  => $user->id,
            'seller_id' => $seller->id,
            'order_id'  => null,
        ]);

        $messages = $conversation->messages()->with('sender')->oldest()->get();

        return view('chat.show', compact('conversation', 'messages', 'seller'))->with('order', null);
    }

    // Store a message and broadcast via WebSocket
    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        if ($user->id !== $conversation->buyer_id && $user->id !== $conversation->seller_id) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:1000']);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body'      => $request->body,
        ]);

        $message->load('sender');

        // Broadcast via WebSocket (Reverb)
        broadcast(new MessageSent($message))->toOthers();

        // Notify the other party
        $recipientId = ($user->id === $conversation->buyer_id)
            ? $conversation->seller_id
            : $conversation->buyer_id;

        Notification::create([
            'user_id' => $recipientId,
            'title'   => 'New Message 💬',
            'body'    => substr($user->name . ': ' . $request->body, 0, 100),
            'type'    => 'chat',
            'data'    => json_encode(['conversation_id' => $conversation->id]),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    // AJAX endpoint to poll messages (fallback if WebSocket not connected)
    public function messages(Conversation $conversation)
    {
        $user = Auth::user();
        if ($user->id !== $conversation->buyer_id && $user->id !== $conversation->seller_id) {
            abort(403);
        }

        $messages = $conversation->messages()->with('sender')->oldest()->get()->map(function ($m) {
            return [
                'id'          => $m->id,
                'body'        => $m->body,
                'sender_id'   => $m->sender_id,
                'sender_name' => $m->sender->name,
                'created_at'  => $m->created_at->format('h:i A'),
            ];
        });

        return response()->json(['messages' => $messages]);
    }
}
