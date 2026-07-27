<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone'          => ['nullable', 'string', 'max:30'],
            'address'        => ['nullable', 'string', 'max:255'],
            'date_of_birth'  => ['nullable', 'date'],
            'gender'         => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'profile_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }
}