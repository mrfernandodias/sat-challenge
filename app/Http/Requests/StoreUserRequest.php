<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email',
      'password' => 'required|string|min:6|confirmed',
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
