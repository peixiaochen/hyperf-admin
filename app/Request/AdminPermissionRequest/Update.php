<?php

declare(strict_types=1);

namespace App\Request\AdminPermissionRequest;

use Hyperf\Validation\Rule;
use App\Request\BaseRequest;
use App\Model\AdminUser;
use App\Model\AdminRole;

class Update extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.id'          => 'required|Integer|exists:admin_permission,id',
            'data.name'        => [
                'required',
                Rule::unique('admin_permission', 'name')->ignore($this->input('data.id')),
            ],
            'data.description' => 'required|string|min:2|max:50',
            'data.http_method' => 'required|string|min:6|max:30',
            'data.http_path'   => 'required:same:password',
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
