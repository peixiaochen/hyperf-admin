<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AdminRole;
use App\Constants\ErrorCode;
use Hyperf\DbConnection\Db;

class AdminRoleController extends AbstractController
{
    public function detail(int $id)
    {
        return $this->rtn_json(AdminRole::getAdminRoleOne($id));
    }

    public function index()
    {
        $param = [
            'page'     => $this->request->input('data.page', 1),
            'limit'    => $this->request->input('data.limit', env('PAGE_NUMBER')),
            'keywords' => addslashes($this->request->input('data.keywords', '')),
        ];
        return $this->rtn_json(AdminRole::getAdminRoleList($param));
    }

    public function save($request)
    {
        $data = [
            'id'          => $request->input('data.id', null),
            'name'        => $request->input('data.name'),
            'description' => $request->input('data.description'),
            'users'       => $request->input('data.users', []),
            'permissions' => $request->input('data.permissions', []),
            'menus'       => $request->input('data.menus', []),
        ];
        Db::beginTransaction();
        try {
            $AdminRole = AdminRole::updateOrCreate([
                'id' => $data['id'],
            ], $data);
            if (!$AdminRole) {
                throw new \Exception(__($data['id'] ? 'messages.admin_common_edit_fail' : 'messages.admin_common_add_fail', ['name' => __('messages.attributes_admin_role')]));
            }
            //关联角色的添加和删除
            $roles = [];
            foreach ($data['users'] as $v) {
                $roles[] = [
                    'role_id' => $AdminRole->id,
                    'user_id' => $v,
                ];
            }
            $AdminRole->roleUser()->delete();
            $data['users'] && $AdminRole->roleUser()->createMany($roles);
            //关联权限的添加和删除
            $permissions = [];
            foreach ($data['permissions'] as $v) {
                $permissions[] = [
                    'role_id'       => $AdminRole->id,
                    'permission_id' => $v,
                ];
            }
            $AdminRole->rolePermission()->delete();
            $data['permissions'] && $AdminRole->rolePermission()->createMany($permissions);
            //菜单权限的添加和删除
            $menus = [];
            foreach ($data['menus'] as $v) {
                $menus[] = [
                    'role_id' => $AdminRole->id,
                    'menu_id' => $v,
                ];
            }
            $AdminRole->roleMenu()->delete();
            $data['menus'] && $AdminRole->roleMenu()->createMany($menus);
            Db::commit();
            return $this->rtn_json('', __($data['id'] ? 'messages.admin_common_edit_success' : 'messages.admin_common_add_success', ['name' => __('messages.attributes_admin_role')]));
        } catch (\Throwable $ex) {
            Db::rollBack();
            return $this->rtn_json($ex->getTraceAsString(), $ex->getMessage(), ErrorCode::REQUEST_TRANSACTION_FAIL);
        }
    }

    public function delete()
    {
        $ids = explode(',', (string)$this->request->input('data.ids', ''));
        if ($ids && AdminRole::destroy($ids)) {
            return $this->rtn_json('', __('messages.admin_common_delete_success', ['name' => __('messages.attributes_admin_role')]));
        } else {
            return $this->rtn_json('', __('messages.admin_common_delete_faild', ['name' => __('messages.attributes_admin_role')]), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }

    public function store(\App\Request\AdminRoleRequest\Store $request)
    {
        return $this->save($request);
    }

    public function update(\App\Request\AdminRoleRequest\Update $request)
    {
        return $this->save($request);
    }
}
