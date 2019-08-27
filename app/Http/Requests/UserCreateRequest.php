<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        
        'username' => 'required|unique:users',
        'password' => 'required|min:4',
        'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'username è richiesto!',
            'password.required' => 'la password è richiesta!',
            'username.unique' => 'username già in uso!',
            'password.min' => 'password troppo corta. 4 caratteri minimo!'
        ];
    }
}
