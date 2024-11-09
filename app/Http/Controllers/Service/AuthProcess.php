<?php

namespace App\Http\Controllers\Service;

use Validator;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\PickedDropMail;
use Illuminate\Support\Facades\Mail;

class AuthProcess 
{
    private $user;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->user = new User();
    }

    public function registeringUser()
    {
        try {
            $this->validateRegistration();
            $user = $this->storeUser();
            return $user;
        } catch (Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception('User registration failed', 400);
        }
    }

    private function validateRegistration()
    {
        $validator = Validator::make($this->data->all(), $this->user::registerRules, $this->user::customMessagesForRegister);
        if (!$validator->passes()) {
            throw new Exception($validator->errors()->first(), 400);
        }
    }

    private function storeUser()
    {
        $user = $this->user->create([
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => Hash::make($this->data['password']),
            'mobile' => $this->data['mobile'],
        ]);

        if (!$user) {
            throw new Exception('Unable to create user', 400);
        }

        // Uncomment and implement OTP if needed
        // $otp = mt_rand(1000, 9999);
        // Mail::to($user->email)->send(new PickedDropMail(['otp' => $otp]));

        return 'User registered successfully. Please verify your email.';
    }

    public function loginProcess()
    {
        try {
            $this->validateLogin();
            $user = $this->performLogin();
            return $user;
        } catch (Exception $e) {
            Log::error('Login failed: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception('Login failed', 400);
        }
    }

    private function validateLogin()
    {
       
        $validator = Validator::make($this->data->all(),$this->user::$loginRules,$this->user::$customMessagesForLogin);

        if (!$validator->passes()) {
            throw new Exception($validator->errors()->first(), 400);
        }
    }

    private function performLogin()
    {
        $credentials = [
            'email' => $this->data->get('email'),
            'password' => $this->data->get('password')
        ];

        if (is_numeric($this->data->get('email'))) {
            $credentials = [
                'mobile' => $this->data->get('email'),
                'password' => $this->data->get('password')
            ];
        }

        if (!Auth::attempt($credentials)) {
            throw new Exception('Invalid credentials', 400);
        }

        $user = Auth::user();
        $user->token = $user->createToken('API Token')->plainTextToken;

        return $user;
    }

    public function changePassword()
    {
        try {
            $this->validateChangePassword();
            $this->updatePassword();
            return 'Password changed successfully';
        } catch (Exception $e) {
            Log::error('Password change failed: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception('Password change failed', 400);
        }
    }

    private function validateChangePassword()
    {
        $validator = Validator::make($this->data->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Please provide email or phone number',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters long',
        ]);

        if (!$validator->passes()) {
            throw new Exception($validator->errors()->first(), 400);
        }
    }

    private function updatePassword()
    {
        $input = $this->data->only('email', 'password');
        $column = is_numeric($input['email']) ? 'mobile' : 'email';

        $updated = $this->user->where($column, $input['email'])->update([
            'password' => Hash::make($input['password']),
            'otp' => null,
        ]);

        if (!$updated) {
            throw new Exception('Unable to update password', 400);
        }
    }

    public function changeUserApproval()
    {
        try {
            $this->validateChangeUserApproval();
            $this->updateUserApproval();
            return 'User approval status updated successfully';
        } catch (Exception $e) {
            Log::error('User approval failed: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception('User approval failed', 400);
        }
    }

    private function validateChangeUserApproval()
    {
        $validator = Validator::make($this->data->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email address',
        ]);

        if (!$validator->passes()) {
            throw new Exception($validator->errors()->first(), 400);
        }
    }

    private function updateUserApproval()
    {
        $updated = $this->user->where('email', $this->data['email'])->update([
            'approved' => true,
            'otp' => null,
        ]);

        if (!$updated) {
            throw new Exception('Unable to update user approval status', 400);
        }
    }
}
