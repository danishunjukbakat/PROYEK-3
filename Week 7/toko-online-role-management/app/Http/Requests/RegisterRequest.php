<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'username' => ['required', 'alpha_dash', 'max:50', 'unique:users,username'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
            'no_hp' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string', 'max:2000'],
            'role' => ['prohibited'],
        ];
    }
}
