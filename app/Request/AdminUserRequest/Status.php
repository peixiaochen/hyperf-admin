<?php

declare(strict_types=1);

namespace App\Request\AdminUserRequest;

use App\Model\AdminUser;
use App\Request\BaseRequest;

class Status extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.ids'    => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $value = explode(',', $value);
                    if (count($value) != AdminUser::query(true)->where([['status', '!=', $this->input('data.status')]])->whereIn('id', $value)->count('id')) {
                        return $fail(__('validation.user_status_count_is_error'));
                    }
                }
            ],
            'data.status' => [
                'required',
                'in:0,1,2'
            ],
        ];
    }
}
