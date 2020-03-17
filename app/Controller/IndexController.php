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

namespace App\Controller;

use App\Model\AdminUser;

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');

        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function welcome()
    {
        //返回用户的个人信息
        //今日新增用户数/今日提现笔数/今日提现总额/今日新增邀请数
        //总新增用户数/总提现笔数/总提现总额/总新增邀请数
        return $this->rtn_json([
            'my_info'      => AdminUser::getAdminUserOne($this->session->get(env('ADMIN_USER_LOGIN_KEY'))),
            'welcome_info' => [
                'today' => [],
                'sum'   => []
            ]
        ]);
    }

    public function qnToken()
    {
        // 用于签名的公钥和私钥
        // 初始化签权对象
        $auth = new \Qiniu\Auth(env('QINIU_ACCESSKEY', 'a'), env('QINIU_SECRETKEY', 'a'));
        // 生成上传Token
        return $this->rtn_json([
            'upload_token' => $auth->uploadToken(env('QINIU_BUCKET_NAME', 'a'), null, env('QINIU_TOKEN_EXPIRES', 3600), null, true),
            'upload_url'   => env('QINIU_UPLOAD_URL', ''),
            'src'          => env('QINIU_SRC', ''),
        ]);
    }

    public function cacheClear()
    {
        return $this->rtn_json('', __('messages.admin_clear_success'));
    }
}
