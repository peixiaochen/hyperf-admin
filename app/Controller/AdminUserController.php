<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AdminUser;
use App\Constants\ErrorCode;
use Hyperf\DbConnection\Db;

class AdminUserController extends AbstractController
{
    public function detail(int $id)
    {
        return $this->rtn_json(AdminUser::getAdminUserOne($id));
    }

    public function updateMyInfo(\App\Request\AdminUserRequest\UpdateMyInfo $request)
    {
        $data = [
            'id'          => $this->session->get(env('ADMIN_USER_LOGIN_KEY')),
            'name'        => $request->input('data.name'),
            'username'    => $request->input('data.username'),
            'password'    => md5(mb_substr(md5($request->input('data.password')), (int)env('PASSWORD_START'), (int)env('PASSWORD_LENGTH'))),
            'avatar'      => $request->input('data.avatar', ''),
            'extra'       => json_encode([
                'email' => $request->input('data.extra.email'),
                'sex'   => $request->input('data.extra.sex'),
                'phone' => $request->input('data.extra.phone'),
            ]),
        ];
        if ($data['id'] && !$request->input('data.password')) {
            unset($data['password']);
        }
        if (AdminUser::query()->where('id', $data['id'])->update($data)) {
            return $this->rtn_json('', __('messages.admin_common_edit_success', ['name' => __('messages.attributes_admin_user')]));
        }
        return $this->rtn_json('', __('messages.admin_common_edit_fail', ['name' => __('messages.attributes_admin_user')]), ErrorCode::REQUEST_TRANSACTION_FAIL);
    }

    public function save($request)
    {
        $data = [
            'id'          => $request->input('data.id', null),
            'name'        => $request->input('data.name'),
            'username'    => $request->input('data.username'),
            'password'    => md5(mb_substr(md5($request->input('data.password')), (int)env('PASSWORD_START'), (int)env('PASSWORD_LENGTH'))),
            'avatar'      => $request->input('data.avatar', ''),
            'extra'       => [
                'email' => $request->input('data.extra.email'),
                'sex'   => $request->input('data.extra.sex'),
                'phone' => $request->input('data.extra.phone'),
            ],
            'roles'       => $request->input('data.roles', []),
            'permissions' => $request->input('data.permissions', []),
        ];
        if ($data['id'] && !$request->input('data.password')) {
            unset($data['password']);
        }
        Db::beginTransaction();
        try {
            $AdminUser = AdminUser::updateOrCreate([
                'id' => $data['id'],
            ], $data);
            if (!$AdminUser) {
                throw new \Exception(__($data['id'] ? 'messages.admin_common_edit_fail' : 'messages.admin_common_add_fail', ['name' => __('messages.attributes_admin_user')]));
            }
            //关联角色修改与添加
            $roles = [];
            foreach ($data['roles'] as $v) {
                $roles[] = [
                    'role_id' => $v,
                    'user_id' => $AdminUser->id,
                ];
            }
            $AdminUser->roleUser()->delete();
            $data['roles'] && $AdminUser->roleUser()->createMany($roles);
            //关联权限修改与添加
//            $permissions = [];
//            foreach ($data['permissions'] as $v) {
//                $permissions[] = [
//                    'permission_id' => $v,
//                    'user_id'       => $AdminUser->id,
//                ];
//            }
//            $AdminUser->userPermission()->delete();
//            $data['permissions'] && $AdminUser->userPermission()->createMany($permissions);
            Db::commit();
            return $this->rtn_json('', __($data['id'] ? 'messages.admin_common_edit_success' : 'messages.admin_common_add_success', ['name' => __('messages.attributes_admin_user')]));
        } catch (\Throwable $ex) {
            Db::rollBack();
            return $this->rtn_json($ex->getTraceAsString(), $ex->getMessage(), ErrorCode::REQUEST_TRANSACTION_FAIL);
        }
    }

    public function index()
    {
        $param = [
            'page'       => $this->request->input('data.page', 1),
            'limit'      => $this->request->input('data.limit', env('PAGE_NUMBER')),
            'keywords'   => addslashes($this->request->input('data.keywords', '')),
            'start_time' => $this->request->input('data.start_time', ''),
            'end_time'   => $this->request->input('data.end_time', ''),
            'role_id'    => $this->request->input('data.role_id', 0),
        ];
        return $this->rtn_json(AdminUser::getAdminUserList($param));
    }

    public function delete()
    {
        $ids = explode(',', (string)$this->request->input('data.ids', ''));
        if ($ids && AdminUser::destroy($ids)) {
            return $this->rtn_json('', __('messages.admin_common_delete_success', ['name' => __('messages.attributes_admin_user')]));
        } else {
            return $this->rtn_json('', __('messages.admin_common_delete_faild', ['name' => __('messages.attributes_admin_user')]), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }

    public function status(\App\Request\AdminUserRequest\Status $request)
    {
        $data = [
            'ids'    => explode(',', (string)$request->input('data.ids', '')),
            'status' => $request->input('data.status')
        ];
        //状态为0，需要禁止用户的所有提现申请
        if ($data['ids'] && AdminUser::query()->whereIn('id', $data['ids'])->update(['status' => $data['status']])) {
            return $this->rtn_json('', __('messages.user_status_set_success'));
        } else {
            return $this->rtn_json('', __('messages.user_status_set_fail'), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }

    public function store(\App\Request\AdminUserRequest\Store $request)
    {
        return $this->save($request);
    }

    public function update(\App\Request\AdminUserRequest\Update $request)
    {
        return $this->save($request);
    }

    /**
     * 登录
     * @param \App\Request\AdminUserRequest\Login $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(\App\Request\AdminUserRequest\Login $request)
    {
        $param = [
            'username' => addslashes($request->input('data.username')),
            'password' => $request->input('data.password'),
        ];
        $this->session->set(env('ADMIN_USER_LOGIN_KEY'), AdminUser::query()->where('username', '=', $param['username'])->value('id'));
        return $this->rtn_json('', __('messages.admin_user_login_success'));
    }

    /**
     * 退出
     */
    public function logout()
    {
        $this->session->remove(env('ADMIN_USER_LOGIN_KEY'));
        return $this->rtn_json('', __('messages.admin_user_logout_success'));
    }
}
