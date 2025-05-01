<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to false to block unauthorized users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'owner' => 'required|string',
            'businessname' => 'required|string',
            'phonenumber' => 'required',
            'mobilenumber' => 'required',
            'state' => 'required',
            'city'=> 'required',
            'zipcode' => 'required',
            'streetname'=> 'required',
            'zip_code_id' => 'required'
        ];
    }
}
