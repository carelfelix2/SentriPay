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
    public $orderNumber;
    public $order;
    public $bankProof;
    public $bankName = '';
    public $accountNumber = '';
    public $transferDate = '';
    public $notes = '';
    public $step = 1; // 1: Instruksi, 2: Upload Bukti

    public function mount(Order $order)
    {
        abort_if($order->buyer_id !== auth()->id(), 403);
        $this->order = $order;
        $this->orderId = $order->id;
        $this->orderNumber = $order->order_number;
    }

    public function submitPaymentProof()
    {
        $this->validate([
            'bankName' => 'required|string',
            'accountNumber' => 'required|string',
            'transferDate' => 'required|date',
            'bankProof' => 'required|image|max:2048',
        ]);

        try {
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
                'status' => 'completed',
                'description' => $this->notes,
                'bank_proof' => $path,
                'confirmed_at' => now(),
            ]);

            // Update order status ke payment_confirmed (pembayaran sudah dikonfirmasi, menunggu pengiriman)
            $this->order->update([
                'status' => 'payment_confirmed',
                'payment_date' => now(),
            ]);

            // Update seller wallet
            $seller = $this->order->seller;
            if ($seller) {
                $seller->update([
                    'balance' => $seller->balance + $this->order->total_amount,
                ]);
            }

            session()->flash('message', 'Pembayaran berhasil dikonfirmasi! Pesanan menunggu pengiriman dari penjual.');
            
            // Use Livewire redirect
            return $this->redirect(route('order.detail', $this->order->order_number), navigate: true);
        } catch (\Exception $e) {
            \Log::error('Payment submission error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->dispatch('payment-error');
        }
    }

    public function render()
    {
        return view('livewire.payment-process');
    }
}
