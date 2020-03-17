<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AdminPermission;
use App\Constants\ErrorCode;
use Hyperf\DbConnection\Db;

class AdminPermissionController extends AbstractController
{
    public function detail(int $id)
    {
        return $this->rtn_json(AdminPermission::getAdminPermissionOne($id));
    }

    public function index()
    {
        $param = [
            'page'     => $this->request->input('data.page', 1),
            'limit'    => $this->request->input('data.limit', env('PAGE_NUMBER')),
            'keywords' => addslashes($this->request->input('data.keywords', '')),
        ];
        return $this->rtn_json(AdminPermission::getAdminPermissionList($param));
    }

    public function save($request)
    {
        $data = [
            'id'          => $request->input('data.id', null),
            'name'        => $request->input('data.name'),
            'description' => $request->input('data.description'),
            'http_method' => $request->input('data.http_method'),
            'http_path'   => $request->input('data.http_path'),
            'roles'       => $request->input('data.roles', []),
            'users'       => $request->input('data.users', []),
        ];
        Db::beginTransaction();
        try {
            $AdminPermission = AdminPermission::updateOrCreate([
                'id' => $data['id'],
            ], $data);
            if (!$AdminPermission) {
                throw new \Exception(__($data['id'] ? 'messages.admin_common_edit_fail' : 'messages.admin_common_add_fail', ['name' => __('messages.attributes_admin_permission')]));
            }
            //关联角色的添加和删除
            $permissions = [];
            foreach ($data['roles'] as $v) {
                $permissions[] = [
                    'role_id'       => $v,
                    'permission_id' => $AdminPermission->id,
                ];
            }
            $AdminPermission->rolePermission()->delete();
            $data['roles'] && $AdminPermission->rolePermission()->createMany($permissions);
            //关联用户的添加和删除
            $roles = [];
            foreach ($data['users'] as $v) {
                $roles[] = [
                    'user_id'       => $v,
                    'permission_id' => $AdminPermission->id,
                ];
            }
            $AdminPermission->userPermission()->delete();
            $data['users'] && $AdminPermission->userPermission()->createMany($roles);
            Db::commit();
            return $this->rtn_json('', __($data['id'] ? 'messages.admin_common_edit_success' : 'messages.admin_common_add_success', ['name' => __('messages.attributes_admin_permission')]));
        } catch (\Throwable $ex) {
            Db::rollBack();
            return $this->rtn_json($ex->getTraceAsString(), $ex->getMessage(), ErrorCode::REQUEST_TRANSACTION_FAIL);
        }
    }

    public function delete()
    {
        $ids = explode(',', (string)$this->request->input('data.ids', ''));
        if ($ids && AdminPermission::destroy($ids)) {
            return $this->rtn_json('', __('messages.admin_common_delete_success', ['name' => __('messages.attributes_admin_permission')]));
        } else {
            return $this->rtn_json('', __('messages.admin_common_delete_faild', ['name' => __('messages.attributes_admin_permission')]), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }

    public function store(\App\Request\AdminPermissionRequest\Store $request)
    {
        return $this->save($request);
    }

    public function update(\App\Request\AdminPermissionRequest\Update $request)
    {
        return $this->save($request);
    }
}
