<?php

namespace modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use modules\Users\Rules\MatchOldPassword;

class UserFormRequest extends FormRequest
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

            return $this->getUserRules($this->input('class'));

    }



    public function getUserRules($class)
    {
        $rules = [];
        switch($class){
            case "register":
                $rules = [
                    'name'                  => 'required',
                    'email'                 => 'required|email|unique:users,email',
                    'password'              => 'required|min:6',
                    'organization_id' => 'required|exists:organizations,id',
                ];
                break;
            case "login":
                $rules = [
                    'email' => 'required|email|exists:customers,email',
                    'password' =>  'required',
                ];
                break;
            case "updatePassword":
                $rules = [
                    'old_password' => ['required', new MatchOldPassword],
                    'new_password' => 'required|min:6'
                ];

            case "userDetailsById":
                $rules = [
                    'user_id' => 'required|exists:users,id',
                ];
                break;
            case "UserUpdateById":
                $rules = [
                    'user_id'             => 'required|exists:users,id',
                    'name'                  => 'required',
                    'email'                 => 'required|email|unique:users,email,'.$this->user_id,
                    'password'              => 'required|min:6',
                    'organization_id'       => 'required|exists:organizations,id',
                ];
                break;
            case "updateUser":
                $rules = [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,'.$user->id,
                    'password' => 'required|min:6',
                    'organization_id' => 'required|exists:organizations,id',
                ];
                break;
            case "softDeleteUser":
                $rules = [
                    'user_id' => 'required|exists:users,id',
                ];
                break;
            case "restoreUser":
                $rules = [
                    'user_id' => 'required|exists:users,id',
                ];
                break;

        }
        return $rules;
    }
}
