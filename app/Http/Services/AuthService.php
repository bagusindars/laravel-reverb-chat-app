<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\GuestSupport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService {
    public function login($email, $password)
    {
        try {
            $sd = Agent::where('email', $email)->first();

            if (!$sd) {
                throw new \Exception("Incorrect email or password");
            }

            if ($sd->password != $password) {
                // for simulate reason, the password not implement hashing method
                throw new \Exception("Incorrect email or password");
            }

            Auth::guard('agent')->login($sd);

            return [
                'status' => true,
                'message' => 'Login successfully'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public function getGuest()
    {
        return GuestSupport::latest()->get();
    }
}