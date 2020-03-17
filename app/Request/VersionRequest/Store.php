<?php

declare(strict_types=1);

namespace App\Request\VersionRequest;

use App\Model\Version;
use App\Request\BaseRequest;
use Hyperf\Validation\Rule;

class Store extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data.dpt'            => 'required|Integer|in:1,2',
            'data.version'        => [
                'required',
                Rule::unique('version', 'version')->where(function ($query) {
                    return $query->where('dpt', $this->input('data.dpt', 1));
                })
            ],
            'data.version_tip'    => [
                'required',
                Rule::unique('version', 'version_tip')->where(function ($query) {
                    return $query->where('dpt', $this->input('data.dpt', 1));
                })
            ],
            'data.min_version_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $last_min_version_id = Version::query()->where([['dpt', '=', $this->input('data.dpt')]])->max('min_version_id') ?: 0;
                    if ($value == 0) {
                        if ($last_min_version_id) {
                            return $fail(__('validation.admin_min_version_id_is_error'));
                        }
                    } else {
                        if (!Version::query()->where([['dpt', '=', $this->input('data.dpt')], ['id', '=', $value], ['id', '>=', $last_min_version_id]])->exists()) {
                            return $fail(__('validation.admin_min_version_id_is_error'));
                        }
                    }
                }
            ],
            'data.apk_url'        => [
                'required_if:data.dpt,2',
                'url',
            ],
            'data.description'    => [
                'required',
                'string',
            ],
            'data.level'          => [
                'required',
                'in:1,2,3,4',
            ],
            'data.extra'          => [
                'nullable'
            ],
        ];
    }
}
