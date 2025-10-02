<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ContactForm extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10|max:2000',
    ];

    public function mount()
    {
        // Pre-fill with user data if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function submitMessage()
    {
        $this->validate();

        ContactMessage::create([
            'customer_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => 'new',
        ]);

        // Reset form
        $this->name = '';
        $this->email = '';
        $this->subject = '';
        $this->message = '';

        // Pre-fill again if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
        }

        session()->flash('success', 'Your message has been sent successfully! We will get back to you soon.');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}