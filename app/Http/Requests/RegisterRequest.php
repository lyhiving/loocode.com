<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'confirmPassword' => 'required|min:6|max:32',
            'email' => 'required|email|max:128',
            'fullName' => 'required|min:6|max:64',
            'password' => 'required|min:6|max:32',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator); // TODO: Change the autogenerated stub
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'fullName.required' => "用户名不能为空",
            'email.required' => "邮箱地址不能为空",
            'password.required' => "密码不能为空",
            'confirmPassword.required' => "确认密码不能为空",
        ];
    }
}
