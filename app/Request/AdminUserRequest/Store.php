<?php

declare(strict_types=1);

namespace App\Request\AdminUserRequest;

use App\Model\AdminPermission;
use App\Model\AdminRole;
use App\Request\BaseRequest;

class Store extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.name'                  => 'required|unique:admin_user,name|min:2|max:50',
            'data.username'              => 'required|alpha_dash|min:2|max:50',
            'data.password'              => 'required|confirmed|alpha_dash|min:6|max:30',
            'data.password_confirmation' => 'required:same:password',
            'data.avatar'                => 'nullable|min:6|max:60',
            'data.roles'                 => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminRole::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_role'));
                    }
                }
            ],
            'data.permissions'           => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminPermission::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_permission'));
                    }
                }
            ],
            'data.extra.phone'           => 'nullable|regex:/^1[3-9][0-9]\d{8}$/',
            'data.extra.email'           => 'nullable|email',
            'data.extra.sex'             => 'required|in:0,1',
        ];
    }
}
