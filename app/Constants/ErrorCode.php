<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;
    /**
     * @Message("系统繁忙，此时请开发者稍候再试")
     */
    const SERVER_BUSY = -1;

    /**
     * @Message("请求成功")
     */
    const SUCCESS = 0;

    /**
     * @Message("登录失效")
     */
    const LOGIN_FAIL = 10101;
    /**
     * @Message("权限不足")
     */
    const PERMISSION_FAIL = 10102;
    /**
     * @Message("访问日志添加失败")
     */
    const PERMISSION_LOG_FAIL = 10103;
    /**
     * @Message("非法的请求参数")
     */
    const REQUEST_INVALID_PARAM = 10200;
    /**
     * @Message("事务提交失败")
     */
    const REQUEST_TRANSACTION_FAIL = 10500;


}
