<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentProcess extends Component
{
    use WithFileUploads;

    public $orderId;
    public $order;
    public $bankProof;
    public $bankName = '';
    public $accountNumber = '';
    public $transferDate = '';
    public $notes = '';
    public $step = 1; // 1: Instruksi, 2: Upload Bukti

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->order = Order::findOrFail($orderId);

        // Pastikan user adalah pembeli
        if ($this->order->buyer_id !== auth()->id()) {
            abort(403);
        }
    }

    public function submitPaymentProof()
    {
        $this->validate([
            'bankName' => 'required|string',
            'accountNumber' => 'required|string',
            'transferDate' => 'required|date',
            'bankProof' => 'required|image|max:2048',
        ]);

        // Simpan bukti transfer
        $path = $this->bankProof->store('proofs', 'public');

        // Buat transaction record
        $transaction = Transaction::create([
            'transaction_number' => 'TXN-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'order_id' => $this->orderId,
            'from_user_id' => $this->order->buyer_id,
            'to_user_id' => $this->order->seller_id,
            'amount' => $this->order->total_amount,
            'type' => 'hold',
            'status' => 'pending',
            'description' => $this->notes,
            'bank_proof' => $path,
        ]);

        // Update order status
        $this->order->update([
            'status' => 'payment_confirmed',
            'payment_date' => now(),
        ]);

        session()->flash('message', 'Bukti transfer berhasil dikirim! Menunggu verifikasi admin.');
        
        return redirect()->route('order.detail', $this->orderId);
    }

    public function render()
    {
        return view('livewire.payment-process');
    }
}
