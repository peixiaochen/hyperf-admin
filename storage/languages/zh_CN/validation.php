<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 必须接受',
    'active_url'           => ':attribute 必须是一个合法的 URL',
    'after'                => ':attribute 必须是 :date 之后的一个日期',
    'after_or_equal'       => ':attribute 必须是 :date 之后或相同的一个日期',
    'alpha'                => ':attribute 只能包含字母',
    'alpha_dash'           => ':attribute 只能包含字母、数字、中划线或下划线',
    'alpha_num'            => ':attribute 只能包含字母和数字',
    'array'                => ':attribute 必须是一个数组',
    'before'               => ':attribute 必须是 :date 之前的一个日期',
    'before_or_equal'      => ':attribute 必须是 :date 之前或相同的一个日期',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file'    => ':attribute 必须在 :min 到 :max kb 之间',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间',
        'array'   => ':attribute 必须在 :min 到 :max 项之间',
    ],
    'boolean'              => ':attribute 字符必须是 true 或 false, 1 或 0',
    'confirmed'            => ':attribute 二次确认不匹配',
    'date'                 => ':attribute 必须是一个合法的日期',
    'date_format'          => ':attribute 与给定的格式 :format 不符合',
    'different'            => ':attribute 必须不同于 :other',
    'digits'               => ':attribute 必须是 :digits 位',
    'digits_between'       => ':attribute 必须在 :min 和 :max 位之间',
    'dimensions'           => ':attribute 具有无效的图片尺寸',
    'distinct'             => ':attribute 字段具有重复值',
    'email'                => ':attribute 必须是一个合法的电子邮件地址',
    'exists'               => '选定的 :attribute 是无效的',
    'file'                 => ':attribute 必须是一个文件',
    'filled'               => ':attribute 的字段是必填的',
    'image'                => ':attribute 必须是 jpg, jpeg, png, bmp 或者 gif 格式的图片',
    'in'                   => '选定的 :attribute 是无效的',
    'in_array'             => ':attribute 字段不存在于 :other',
    'integer'              => ':attribute 必须是个整数',
    'ip'                   => ':attribute 必须是一个合法的 IP 地址',
    'json'                 => ':attribute 必须是一个合法的 JSON 字符串',
    'max'                  => [
        'numeric' => ':attribute 的最大值为 :max',
        'file'    => ':attribute 的最大为 :max kb',
        'string'  => ':attribute 的最大长度为 :max 字符',
        'array'   => ':attribute 至多有 :max 项',
    ],
    'mimes'                => ':attribute 的文件类型必须是 :values',
    'min'                  => [
        'numeric' => ':attribute 的最小值为 :min',
        'file'    => ':attribute 大小至少为 :min kb',
        'string'  => ':attribute 的最小长度为 :min 字符',
        'array'   => ':attribute 至少有 :min 项',
    ],
    'not_in'               => '选定的 :attribute 是无效的',
    'numeric'              => ':attribute 必须是数字',
    'present'              => ':attribute 字段必须存在',
    'regex'                => ':attribute 格式是无效的',
    'required'             => ':attribute 字段是必须的',
    'required_if'          => ':attribute 字段是必须的当 :other 是 :value',
    'required_unless'      => ':attribute 字段是必须的，除非 :other 是在 :values 中',
    'required_with'        => ':attribute 字段是必须的当 :values 是存在的',
    'required_with_all'    => ':attribute 字段是必须的当 :values 是存在的',
    'required_without'     => ':attribute 字段是必须的当 :values 是不存在的',
    'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
    'same'                 => ':attribute 和 :other 必须匹配',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size',
        'file'    => ':attribute 必须是 :size kb',
        'string'  => ':attribute 必须是 :size 个字符',
        'array'   => ':attribute 必须包括 :size 项',
    ],
    'string'               => ':attribute 必须是一个字符串',
    'timezone'             => ':attribute 必须是个有效的时区',
    'unique'               => ':attribute 已存在',
    'uploaded'             => ':attribute 上传失败',
    'url'                  => ':attribute 无效的格式',
    'max_if'               => [
        'numeric' => '当 :other 为 :value 时 :attribute 不能大于 :max',
        'file'    => '当 :other 为 :value 时 :attribute 不能大于 :max kb',
        'string'  => '当 :other 为 :value 时 :attribute 不能大于 :max 个字符',
        'array'   => '当 :other 为 :value 时 :attribute 最多只有 :max 个单元',
    ],
    'min_if'               => [
        'numeric' => '当 :other 为 :value 时 :attribute 必须大于等于 :min',
        'file'    => '当 :other 为 :value 时 :attribute 大小不能小于 :min kb',
        'string'  => '当 :other 为 :value 时 :attribute 至少为 :min 个字符',
        'array'   => '当 :other 为 :value 时 :attribute 至少有 :min 个单元',
    ],
    'between_if'           => [
        'numeric' => '当 :other 为 :value 时 :attribute 必须介于 :min - :max 之间',
        'file'    => '当 :other 为 :value 时 :attribute 必须介于 :min - :max kb 之间',
        'string'  => '当 :other 为 :value 时 :attribute 必须介于 :min - :max 个字符之间',
        'array'   => '当 :other 为 :value 时 :attribute 必须只有 :min - :max 个单元',
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes'       => [
        'data.id'                    => '业务ID',
        'data.username'              => '用户名',
        'data.password'              => '密码',
        'data.password_confirmation' => '确认密码',
        'data.parent_id'             => '父级ID',
        'data.title'                 => '标题',
        'data.icon'                  => 'ICON',
        'data.uri'                   => '路径',
        'data.name'                  => '名称',
        'data.avatar'                => '头像',
        'data.roles'                 => '角色数组',
        'data.permissions'           => '权限',
    ],
    'phone_number'     => ':attribute 必须为一个有效的电话号码',
    'telephone_number' => ':attribute 必须为一个有效的手机号码',

    'chinese_word'                   => ':attribute 必须包含以下有效字符 (中文/英文，数字, 下划线)',
    'sequential_array'               => ':attribute 必须是一个有序数组',
    //自定义的验证错误消息
    'admin_user_noexists_username'   => '用户名不存在',
    'admin_user_noexists_role'       => '角色不存在',
    'admin_user_noexists_permission' => '权限不存在',
    'admin_user_noexists_menu'       => '菜单不存在',
    'admin_user_noexists_user'       => '用户不存在',
    'admin_user_mismatch_password'   => '用户名或密码不正确',
    'admin_user_login_success'       => '登录成功',
    'user_status_count_is_error'     => '操作状态有误或者不需要修改',
    'admin_min_version_id_is_error'  => '最低版本号有误',

];
