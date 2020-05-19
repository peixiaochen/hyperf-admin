<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Constants\ErrorCode;
use App\Middleware\BaseMiddleware;
use App\Model\AdminMenuPermission;
use App\Model\AdminOperationLog;
use App\Model\AdminPermission;
use App\Model\AdminRoleMenu;
use App\Model\AdminRolePermission;
use App\Model\AdminRoleUser;
use App\Model\AdminUserPermission;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckPermissionMiddleware extends BaseMiddleware
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = [
            'path'    => mb_substr($this->request->path(), 2),
            'user_id' => $this->session->get(env('ADMIN_USER_LOGIN_KEY')),
            'method'  => $this->request->getMethod(),
            'ip'      => $this->request->header('x-real-ip'),
            'input'   => [
                'data' => $this->request->all()
            ],
        ];
        $path = $data['path'];
        if ($offset = mb_strpos($data['path'], '/', mb_strpos($data['path'], '/', 2) + 1)) {
            $path = mb_substr($path, 0, $offset);
        }
        if ($data['user_id'] > 1) {
            $role_ids = AdminRoleUser::query(true)->where('user_id', $data['user_id'])->pluck('role_id');
            //判断用户是否有权限
            $perimission = AdminPermission::query(true)
                ->select([
                    'id',
                    'name',
                    'http_method',
                    'http_path',
                ])
                ->where([
                    ['http_path', 'like', "%{$path}%"]
                ])
                ->whereIn('id', array_unique(array_merge(
                    AdminRolePermission::query(true)->whereIn('role_id', $role_ids)->pluck('permission_id')->toArray(),
                    AdminMenuPermission::query(true)->whereIn('menu_id', AdminRoleMenu::query(true)->whereIn('role_id', $role_ids)->pluck('menu_id'))->pluck('permission_id')->toArray(),
                    AdminUserPermission::query(true)->where('user_id', $data['user_id'])->pluck('permission_id')->toArray()
                )))
                ->first();
            if (!$perimission) {
                return $this->rtn_json(ErrorCode::PERMISSION_FAIL, ErrorCode::getMessage(ErrorCode::PERMISSION_FAIL));
            }
        } else {
            $perimission = AdminPermission::query(true)
                ->select([
                    'id',
                    'name',
                    'http_method',
                    'http_path',
                ])
                ->where([
                    ['http_path', 'like', "%{$path}%"]
                ])->first();
        }
        $data['input']['permission'] = $perimission->toArray();
        if (!AdminOperationLog::create($data)) {
            return $this->rtn_json(ErrorCode::PERMISSION_LOG_FAIL, ErrorCode::getMessage(ErrorCode::PERMISSION_LOG_FAIL));
        }
        //待完善操作日志
        return $handler->handle($request);
    }
}