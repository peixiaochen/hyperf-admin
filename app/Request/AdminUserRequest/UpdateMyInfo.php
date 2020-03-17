<?php

declare(strict_types=1);

namespace App\Request\AdminUserRequest;

use App\Model\AdminPermission;
use App\Model\AdminRole;
use App\Request\BaseRequest;
use Hyperf\Validation\Rule;

class UpdateMyInfo extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.name'                  => [
                'required',
                'min:2',
                'max:50',
                Rule::unique('admin_user', 'name')->ignore($this->input('data.id')),
            ],
            'data.username'              => 'required|alpha_dash|min:2|max:50',
            'data.password'              => 'nullable|confirmed|alpha_dash|min:6|max:30',
            'data.password_confirmation' => 'nullable:same:password',
            'data.avatar'                => 'nullable|min:6|max:60',
            'data.extra.phone'           => 'nullable|regex:/^1[3-9][0-9]\d{8}$/',
            'data.extra.email'           => 'nullable|email',
            'data.extra.sex'             => 'required|in:0,1',
        ];
    }
}
