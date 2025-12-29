<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Dispute;
use Livewire\Component;
use Livewire\WithFileUploads;

class DisputeManager extends Component
{
    use WithFileUploads;

    public $orderId;
    public $order;
    public $reason = '';
    public $description = '';
    public $evidence;
    public $showForm = false;
    public $disputes = [];

    public function mount($orderId = null)
    {
        if ($orderId) {
            $this->orderId = $orderId;
            $this->order = Order::findOrFail($orderId);
        }

        $this->loadDisputes();
    }

    public function loadDisputes()
    {
        $user = auth()->user();
        $this->disputes = Dispute::where(function ($query) use ($user) {
            $query->where('complained_by', $user->id)
                  ->orWhere('complained_against', $user->id);
        })->latest()->get();
    }

    public function createDispute()
    {
        $this->validate([
            'reason' => 'required|string',
            'description' => 'required|string|min:20',
            'evidence' => 'nullable|image|max:2048',
        ]);

        $evidencePath = null;
        if ($this->evidence) {
            $evidencePath = $this->evidence->store('disputes', 'public');
        }

        Dispute::create([
            'dispute_number' => 'DSP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'order_id' => $this->orderId,
            'complained_by' => auth()->id(),
            'complained_against' => $this->order->seller_id,
            'reason' => $this->reason,
            'complaint_description' => $this->description,
            'status' => 'open',
            'evidence' => $evidencePath,
        ]);

        session()->flash('message', 'Komplain berhasil diajukan. Admin akan meninjau dalam 24 jam.');
        $this->reset(['reason', 'description', 'evidence', 'showForm']);
        $this->loadDisputes();
    }

    public function render()
    {
        return view('livewire.dispute-manager');
    }
}
