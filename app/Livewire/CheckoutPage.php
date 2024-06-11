<?php

namespace App\Livewire;

use App\Models\Address;
use Livewire\Component;
use App\Models\Transaction;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class CheckoutPage extends Component
{
    use WithFileUploads;
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $proof_of_payment;

    public function mount()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        if (count($cart_items) == 0) {
            return redirect('/');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'proof_of_payment' => 'required|mimes:jpg,jpeg,png|file',
        ]);

        $cart_items = CartManagement::getCartItemsFromCookie();

        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'IDR',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['menu_id'],
                    ]
                ],
                'quantity' => $item['quantity'],
            ];
        }
        $proof_of_payment = $this->proof_of_payment->store('proof_of_payment', 'public');
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $transaction->status = 'waiting';
        $transaction->proof_of_payment = $proof_of_payment;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;

        $redirect_url = '';


        $redirect_url = route('my-orders');

        $transaction->save();
        $address->transaction_id = $transaction->id;
        $address->save();

        //        $transaction->items()->createMany($cart_items);
        foreach ($cart_items as $item) {
            $transaction->items()->create([
                'transaction_id' => $transaction->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item['unit_amount'],
                'total_amount' => $item['total_amount'],
            ]);
        }

        CartManagement::clearCartItems();
        // Mail::to(auth()->user()->email)->send(new TransactionPlaced($transaction));
        return redirect($redirect_url);
    }
    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }
}
