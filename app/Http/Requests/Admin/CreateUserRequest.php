<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'username' => [
                'required',
                'max:255',
//                Rule::unique('users')->ignore($this->user()->id, 'id')->where(function ($query) {
//                   return $query->whereNull('deleted_at');
//                }),
                'unique:users,username,{$id},id,deleted_at,NULL',
            ],
            'password' => 'required|same:repassword|min:6|max:255',
            'email' => [
                'required',
                'max:255',
                'email',
//                Rule::unique('users')->ignore($this->user()->id, 'id')->where(function ($query) {
//                   return $query->whereNull('deleted_at');
//                }),
                'unique:users,username,{$id},id,deleted_at,NULL',
            ],
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'description' => 'max:10000',
            'manager' => [
                'sometimes',
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role_id', function ($query) {
                        $query->select('id')->from('roles')->where('role_name', 'manager');
                    });
                }),
            ],
            'role' => Rule::exists('roles', 'id')->where(function ($query) {
                $query->where('role_name', '!=', 'admin');
            }),
        ];
    }
}
