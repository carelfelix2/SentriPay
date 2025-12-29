<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class WalletManager extends Component
{
    public $user;
    public $balance = 0;
    public $withdrawAmount = 0;
    public $withdrawMethod = 'bank_transfer';
    public $bankName = '';
    public $accountNumber = '';
    public $accountHolder = '';

    public function mount()
    {
        $this->user = auth()->user();
        $this->balance = $this->user->balance;
        $this->bankName = $this->user->bank_name;
        $this->accountNumber = $this->user->bank_account;
        $this->accountHolder = $this->user->account_holder;
    }

    public function requestWithdraw()
    {
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:10000|max:' . $this->balance,
            'bankName' => 'required|string',
            'accountNumber' => 'required|string',
            'accountHolder' => 'required|string',
        ]);

        if ($this->withdrawAmount > $this->balance) {
            session()->flash('error', 'Saldo tidak mencukupi');
            return;
        }

        // Create withdrawal request
        // TODO: Create WithdrawalRequest model & table
        // WithdrawalRequest::create([
        //     'user_id' => $this->user->id,
        //     'amount' => $this->withdrawAmount,
        //     'status' => 'pending',
        //     'bank_name' => $this->bankName,
        //     'account_number' => $this->accountNumber,
        //     'account_holder' => $this->accountHolder,
        // ]);

        session()->flash('message', 'Permintaan penarikan diajukan. Akan diproses dalam 1-3 hari kerja');
        $this->reset(['withdrawAmount']);
    }

    public function render()
    {
        return view('livewire.wallet-manager');
    }
}
