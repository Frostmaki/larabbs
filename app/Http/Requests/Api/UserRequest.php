<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
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
            //
            'name'          =>'required|unique:users|string|max:20',
            'email'         =>'required|email|unique:users',
            'password'     =>'required|min:6|string'

        ];
    }
    public function messages()
    {
        return [
            'name.unique'      =>'用户名已被占用',
            'email.unique'     =>'邮箱已被占用',
            'name.required'    =>'用户名不可为空',
            'email.required'    =>'邮箱不可为空',
            'password.required'    =>'密码不可为空',
            'name.max'    =>'用户名不可超过20字符',
            'password.min'  =>'密码最少6位'
        ];

    }
}
