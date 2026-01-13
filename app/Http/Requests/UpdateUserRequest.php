<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'email' => [
        'required',
        'email',
        'max:255',
        Rule::unique('users', 'email')->ignore($this->user),
      ],
      'password' => 'nullable|string|min:6|confirmed',
    ];
  }

  public function messages(): array
  {
    return [
      'password.confirmed' => 'As senhas não conferem.',
      'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
    ];
  }
}
