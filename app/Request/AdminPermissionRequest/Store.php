<?php

declare(strict_types=1);

namespace App\Request\AdminPermissionRequest;

use App\Request\BaseRequest;

use App\Model\AdminUser;
use App\Model\AdminRole;

class Store extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.name'        => 'required|unique:admin_permission,name|min:2|max:50',
            'data.description' => 'required|string|min:2|max:50',
            'data.http_method' => 'required|string|min:2|max:30',
            'data.http_path'   => 'required|min:6|max:255',
            'data.users'       => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminUser::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_user'));
                    }
                }
            ],
            'data.roles'       => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminRole::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_role'));
                    }
                }
            ]
        ];
    }
}
