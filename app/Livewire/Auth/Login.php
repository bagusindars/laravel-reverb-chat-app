<?php

namespace App\Livewire\Auth;

use App\Http\Services\AuthService;
use Livewire\Component;

class Login extends Component
{
    public $email, $password;

    protected AuthService $authService;

    public function boot(
        AuthService $authService
    )
    {
        $this->authService = $authService;
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.app');
    }

    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }

    public function submit()
    {
        $this->resetErrorBag();
        $this->validate();

        $payload = $this->authService->login(
            email: $this->email,
            password: $this->password
        );

        $this->dispatch('notify',
            type: $payload['status'] ? 'success' : 'error',
            content: $payload['message'],
        ); 

        if ($payload['status']) {
            return redirect()->route('master-customer');
        }

        return;
    }
}
