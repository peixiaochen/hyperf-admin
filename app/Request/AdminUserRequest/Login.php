<?php

declare(strict_types=1);

namespace App\Request\AdminUserRequest;

use App\Request\BaseRequest;
use App\Model\AdminUser;

class Login extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.username' => [
                'required',
                'alpha_num',
                'max:16',
                function ($attribute, $value, $fail) {
                    if (!AdminUser::query()->where([
                        ['username', '=', addslashes($value)],
                        ['status', '=', 1],
                    ])->value('id')) {
                        return $fail(__('validation.admin_user_noexists_username'));
                    }
                }
            ],
            'data.password' => [
                'required',
                'alpha_dash',
                'max:32',
                function ($attribute, $value, $fail) {
                    if (AdminUser::query()->where([
                            ['username', '=', addslashes($this->input('data.username'))],
                            ['status', '=', 1],
                        ])->value('password') != md5(mb_substr(md5($value), (int)env('PASSWORD_START'), (int)env('PASSWORD_LENGTH')))) {
                        return $fail(__('validation.admin_user_mismatch_password'));
                    }
                }
            ],
        ];
    }
}
