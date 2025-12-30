<?php

namespace App\Http\Controllers;

use App\Models\ChatGroup;
use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatGroupController extends Controller
{
    /**
     * Create or get existing chat group for an order
     */
    public function startChat(Order $order)
    {
        // Check if user is seller or buyer in this order
        if (Auth::id() !== $order->seller_id && Auth::id() !== $order->buyer_id) {
            abort(403, 'Unauthorized');
        }

        // Check if order status is ready for shipping (payment confirmed, shipped, or delivered)
        $allowedStatuses = ['payment_confirmed', 'shipped', 'delivered', 'completed'];
        if (!in_array($order->status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Order harus dalam status pembayaran terkonfirmasi atau lebih untuk membuka chat');
        }

        // Get or create chat group
        $chatGroup = ChatGroup::where('order_id', $order->id)->first();

        if (!$chatGroup) {
            $chatGroup = ChatGroup::create([
                'order_id' => $order->id,
                'seller_id' => $order->seller_id,
                'buyer_id' => $order->buyer_id,
                'admin_id' => null, // Admin akan ditambahkan nanti
                'name' => "{$order->product->name} - Rp " . number_format($order->total_amount, 0, ',', '.'),
                'status' => 'open'
            ]);
        }

        return redirect()->route('chat.show', $chatGroup);
    }

    /**
     * Display the chat group
     */
    public function show(ChatGroup $chatGroup)
    {
        // Check if user is authorized
        if (!$this->isAuthorizedUser($chatGroup)) {
            abort(403, 'Unauthorized');
        }

        // Check if chat is closed
        if ($chatGroup->status === 'closed') {
            return view('chat.closed', ['chatGroup' => $chatGroup]);
        }

        $messages = $chatGroup->messages()->with('user')->get();

        return view('chat.show', [
            'chatGroup' => $chatGroup,
            'messages' => $messages,
            'order' => $chatGroup->order
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, ChatGroup $chatGroup)
    {
        // Check if user is authorized
        if (!$this->isAuthorizedUser($chatGroup)) {
            abort(403, 'Unauthorized');
        }

        // Check if chat is still open
        if ($chatGroup->status === 'closed') {
            return redirect()->back()->with('error', 'Chat telah ditutup');
        }

        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        // Determine user role
        $userRole = Auth::id() === $chatGroup->seller_id ? 'seller' : 'buyer';

        ChatMessage::create([
            'chat_group_id' => $chatGroup->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'user_role' => $userRole
        ]);

        return redirect()->route('chat.show', $chatGroup)->with('success', 'Pesan terkirim');
    }

    /**
     * Buyer confirms they received the account
     */
    public function confirmAccountReceived(ChatGroup $chatGroup)
    {
        // Only buyer can confirm
        if (Auth::id() !== $chatGroup->buyer_id) {
            abort(403, 'Hanya pembeli yang dapat mengkonfirmasi');
        }

        // Chat group must be open
        if ($chatGroup->status === 'closed') {
            return redirect()->back()->with('error', 'Chat telah ditutup');
        }

        // Add system message
        ChatMessage::create([
            'chat_group_id' => $chatGroup->id,
            'user_id' => Auth::id(),
            'message' => 'Saya telah menerima akun/detail produk dengan baik. Transaksi selesai.',
            'user_role' => 'buyer'
        ]);

        // Confirm and close
        $chatGroup->confirmBuyerReceived();

        return redirect()->route('chat.show', $chatGroup)->with('success', 'Akun telah dikonfirmasi diterima. Chat ditutup dan status order diubah menjadi "Barang Diterima"');
    }

    /**
     * Check if current user is authorized to access this chat
     */
    private function isAuthorizedUser(ChatGroup $chatGroup): bool
    {
        $userId = Auth::id();
        return $userId === $chatGroup->seller_id || 
               $userId === $chatGroup->buyer_id || 
               (Auth::user()->role === 'admin' && $userId === $chatGroup->admin_id);
    }
}
