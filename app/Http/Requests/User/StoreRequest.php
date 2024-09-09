<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use App\Rules\ExistRoleRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['required','string'],
            'email'=>['required','email','unique:users,email'],
            'national_id'=>['required','string','regex:/^[0-9][0-9]*$/','unique:users,national_id'],
            'is_male'=>['in:male,female'],
            'role'=>['required','string']
        ];
    }
    public function passedValidation()
    {
        $this->merge([
           'role' => Role::findByName($this->role)
        ]);
    }

}
