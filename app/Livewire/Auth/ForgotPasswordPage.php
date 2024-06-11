<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Forgot Password')]
class ForgotPasswordPage extends Component
{
    public $email;
    use livewireAlert;

    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255'
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->alert('success', 'Password reset link has been sent to your email.', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
            $this->email = '';
        }
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
