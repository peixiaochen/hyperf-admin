<?php

declare(strict_types=1);

namespace App\Request\AdminMenuRequest;

use App\Request\BaseRequest;
use App\Model\AdminRole;
use App\Model\AdminPermission;

class Store extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.parent_id' => 'required|Integer|exists:admin_menu,id',
            'data.title'     => 'required|min:2|max:20',
            'data.icon'      => 'required|min:2|max:20',
            'data.uri'       => 'required|min:2|max:50',
            'data.roles'     => [
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
        ];
    }
}
