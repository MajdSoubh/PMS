<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique("roles", "name")->where("pharmacy_id", auth()->user()->pharmacy->id)->ignore($this->role)],
            'permissions' => 'array|min:1',
            'permissions.*.permission' => 'exists:permissions,id'
        ];
    }
}
