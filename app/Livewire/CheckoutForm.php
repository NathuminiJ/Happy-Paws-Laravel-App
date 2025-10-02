<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Shipment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutForm extends Component
{
    public $cart = [];
    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 0;
    public $total = 0;

    // Customer information
    public $email = '';
    public $phone = '';
    public $firstName = '';
    public $lastName = '';

    // Shipping information
    public $shippingAddress1 = '';
    public $shippingAddress2 = '';
    public $shippingCity = '';
    public $shippingState = '';
    public $shippingZip = '';
    public $shippingCountry = 'US';

    // Payment information
    public $paymentMethod = 'credit_card';
    public $cardNumber = '';
    public $cardExpiry = '';
    public $cardCvv = '';
    public $cardName = '';

    // Billing information
    public $billingSameAsShipping = true;
    public $billingAddress1 = '';
    public $billingAddress2 = '';
    public $billingCity = '';
    public $billingState = '';
    public $billingZip = '';
    public $billingCountry = 'US';

    protected $rules = [
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'shippingAddress1' => 'required|string|max:255',
        'shippingCity' => 'required|string|max:255',
        'shippingState' => 'required|string|max:255',
        'shippingZip' => 'required|string|max:20',
        'shippingCountry' => 'required|string|max:2',
        'paymentMethod' => 'required|string',
    ];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        
        if (empty($this->cart)) {
            return redirect()->route('products');
        }

        $this->calculateTotals();

        // Pre-fill with user data if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->email = $user->email;
            $this->firstName = $user->name;
            $this->phone = $user->phone ?? '';
            $this->shippingAddress1 = $user->address ?? '';
            $this->shippingCity = $user->city ?? '';
            $this->shippingState = $user->state ?? '';
            $this->shippingZip = $user->zip_code ?? '';
            $this->shippingCountry = $user->country ?? 'US';
        }
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        
        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }

        $this->tax = $this->subtotal * 0.08; // 8% tax
        $this->shipping = $this->subtotal >= 50 ? 0 : 10; // Free shipping over $50
        $this->total = $this->subtotal + $this->tax + $this->shipping;
    }

    public function updatedBillingSameAsShipping()
    {
        if ($this->billingSameAsShipping) {
            $this->billingAddress1 = $this->shippingAddress1;
            $this->billingAddress2 = $this->shippingAddress2;
            $this->billingCity = $this->shippingCity;
            $this->billingState = $this->shippingState;
            $this->billingZip = $this->shippingZip;
            $this->billingCountry = $this->shippingCountry;
        }
    }

    public function placeOrder()
    {
        $this->validate();

        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'customer_id' => Auth::id(),
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax,
                'shipping_amount' => $this->shipping,
                'total_amount' => $this->total,
                'status' => 'pending',
            ]);

            // Create order details
            foreach ($this->cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
            }

            // Create payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $this->paymentMethod,
                'amount' => $this->total,
                'status' => 'pending',
                'payment_details' => [
                    'card_last_four' => substr($this->cardNumber, -4),
                    'card_name' => $this->cardName,
                ],
            ]);

            // Create shipment
            Shipment::create([
                'order_id' => $order->id,
                'address_line1' => $this->shippingAddress1,
                'address_line2' => $this->shippingAddress2,
                'city' => $this->shippingCity,
                'state' => $this->shippingState,
                'zip_code' => $this->shippingZip,
                'country' => $this->shippingCountry,
                'mobile_number' => $this->phone,
                'status' => 'pending',
            ]);

            // Simulate payment processing
            $payment->update([
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'processed_at' => now(),
            ]);

            $order->update(['status' => 'processing']);

            DB::commit();

            // Clear cart
            session()->forget('cart');

            session()->flash('success', 'Order placed successfully! Order #' . $order->order_number);
            
            return redirect()->route('orders.show', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to place order. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.checkout-form');
    }
}