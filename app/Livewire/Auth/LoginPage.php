<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LoginPage extends Component
{
    use livewireAlert;
    public $email;
    public $password;

    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'role' => 'user'])) {
            $this->alert('error', 'invalid username or password !', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
            return;
        }
        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
