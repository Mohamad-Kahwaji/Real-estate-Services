<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = auth('users')->id();

        return [
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'email'    => ['nullable', 'string', 'lowercase', 'email', 'max:255',
                           Rule::unique(User::class)->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}
