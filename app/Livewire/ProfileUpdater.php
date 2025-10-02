<?php

namespace App\Livewire;

use App\Models\Subscription;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileUpdater extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $dateOfBirth = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zipCode = '';
    public $country = 'US';

    // Password change fields
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';

    // Subscription fields
    public $subscription = null;
    public $showPasswordForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'dateOfBirth' => 'nullable|date|before:today',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'zipCode' => 'nullable|string|max:20',
        'country' => 'required|string|max:2',
    ];

    protected $passwordRules = [
        'currentPassword' => 'required|string',
        'newPassword' => 'required|string|min:8|confirmed',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->dateOfBirth = $user->date_of_birth?->format('Y-m-d') ?? '';
        $this->address = $user->address ?? '';
        $this->city = $user->city ?? '';
        $this->state = $user->state ?? '';
        $this->zipCode = $user->zip_code ?? '';
        $this->country = $user->country ?? 'US';

        // Load subscription if exists
        $this->subscription = $user->subscriptions()->active()->first();
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth ?: null,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'country' => $this->country,
        ]);

        session()->flash('success', 'Profile updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules);

        $user = Auth::user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
        $this->showPasswordForm = false;

        session()->flash('success', 'Password updated successfully!');
    }

    public function togglePasswordForm()
    {
        $this->showPasswordForm = !$this->showPasswordForm;
        $this->resetPasswordFields();
    }

    public function resetPasswordFields()
    {
        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
        $this->resetErrorBag(['currentPassword', 'newPassword', 'confirmPassword']);
    }

    public function subscribeToPlan($planName)
    {
        $plans = [
            'basic' => ['price' => 9.99, 'features' => ['Basic support', 'Email notifications']],
            'premium' => ['price' => 19.99, 'features' => ['Priority support', 'SMS notifications', 'Exclusive products']],
            'pro' => ['price' => 39.99, 'features' => ['24/7 support', 'All notifications', 'All products', 'Free shipping']],
        ];

        if (!isset($plans[$planName])) {
            session()->flash('error', 'Invalid plan selected.');
            return;
        }

        $plan = $plans[$planName];
        $user = Auth::user();

        // Cancel existing subscription
        if ($this->subscription) {
            $this->subscription->update(['status' => 'cancelled']);
        }

        // Create new subscription
        Subscription::create([
            'customer_id' => $user->id,
            'plan_name' => $planName,
            'price' => $plan['price'],
            'status' => 'active',
            'start_date' => now(),
            'next_billing_date' => now()->addMonth(),
            'features' => $plan['features'],
        ]);

        $this->subscription = $user->subscriptions()->active()->first();
        session()->flash('success', 'Successfully subscribed to ' . ucfirst($planName) . ' plan!');
    }

    public function cancelSubscription()
    {
        if ($this->subscription) {
            $this->subscription->update(['status' => 'cancelled']);
            $this->subscription = null;
            session()->flash('success', 'Subscription cancelled successfully!');
        }
    }

    public function render()
    {
        return view('livewire.profile-updater');
    }
}