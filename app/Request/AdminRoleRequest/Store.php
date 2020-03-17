<?php

declare(strict_types=1);

namespace App\Request\AdminRoleRequest;

use App\Model\AdminUser;
use App\Request\BaseRequest;
use App\Model\AdminPermission;
use App\Model\AdminMenu;

class Store extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.name'        => 'required|unique:admin_role,name|min:2|max:20',
            'data.description' => 'nullable|min:2|max:20',
            'data.users'       => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminUser::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_user'));
                    }
                }
            ],
            'permissions'      => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminPermission::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_permission'));
                    }
                }
            ],
            'menus'            => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) != AdminMenu::query()->whereIn('id', $value)->count()) {
                        return $fail(__('validation.admin_user_noexists_menu'));
                    }
                }
            ],
        ];
    }
}
