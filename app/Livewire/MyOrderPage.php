<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title('My Orders')]
class MyOrderPage extends Component
{
    use WithPagination;
    use livewireAlert;
    public $transaction_id;

    public function setTransactionAndCancel($transaction_id)
    {
        $this->transaction_id = $transaction_id;
        $this->cancelOrder();
    }

    public function cancelOrder()
    {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction && $transaction->user_id == auth()->user()->id) {
            $transaction->status = 'canceled';
            $transaction->save();

            $this->alert('success', 'Transaction already Cancelled', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        } else {
            $this->alert('error', 'Transaction could not be canceled.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        }

        return redirect()->route('my-orders');
    }

    public function render()
    {
        $my_transaction = Transaction::where('user_id', auth()->user()->id)->latest()->paginate(5);
        return view('livewire.my-order-page', [
            'orders' => $my_transaction
        ]);
    }
}
