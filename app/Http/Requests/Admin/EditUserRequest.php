<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditUserRequest extends FormRequest
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
        if ($this->password || $this->repassword) {
            $passworRules = 'same:repassword|min:6|max:255';
        } else {
            $passworRules = '';
        }
        return [
            'username' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($this->user, 'id')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'password' => $passworRules,
            'email' => [
                'required',
                'max:255',
                'email',
                Rule::unique('users')->ignore($this->user, 'id')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
//            'avatar' => 'file',
            'description' => 'max:10000',
            'manager' => [
                'sometimes',
                'nullable',
//                Rule::exists('roles', 'id')->where(function ($query) {
//                    $query->where('role_name', 'leader');
//                }),
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
