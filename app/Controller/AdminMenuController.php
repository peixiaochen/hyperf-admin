<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AdminMenu;
use App\Constants\ErrorCode;
use Hyperf\DbConnection\Db;

class AdminMenuController extends AbstractController
{

    public function sort()
    {
        $order_id = $this->request->input('data.order_id', []);
        if (!empty($order_id) && AdminMenu::sortAdminMenu($order_id)) {
            return $this->rtn_json('', __('messages.admin_common_sort_success', ['name' => __('messages.attributes_admin_menu')]));
        }
        return $this->rtn_json('', __('messages.admin_common_sort_fail', ['name' => __('messages.attributes_admin_menu')]), ErrorCode::REQUEST_INVALID_PARAM);
    }


    public function save($request)
    {
        $data = [
            'id'          => $request->input('data.id', null),
            'parent_id'   => $request->input('data.parent_id', 0),
            'title'       => $request->input('data.title'),
            'icon'        => $request->input('data.icon'),
            'uri'         => $request->input('data.uri'),
            'roles'       => $request->input('data.roles', []),
            'permissions' => $request->input('data.permissions', []),
            'order'       => 0,
        ];
        Db::beginTransaction();
        try {
            $AdminMenu = AdminMenu::updateOrCreate([
                'id' => $data['id'],
            ], $data);
            if (!$AdminMenu) {
                throw new \Exception(__($data['id'] ? 'messages.admin_common_edit_fail' : 'messages.admin_common_add_fail', ['name' => __('messages.attributes_admin_menu')]));
            }
            //关联角色修改与添加
//            $roles = [];
//            foreach ($data['roles'] as $v) {
//                $roles[] = [
//                    'role_id' => $v,
//                    'menu_id' => $AdminMenu->id,
//                ];
//            }
//            $AdminMenu->menuRoles()->delete();
//            $data['roles'] && $AdminMenu->menuRoles()->createMany($roles);
            //关联权限修改与添加
            $permissions = [];
            foreach ($data['permissions'] as $v) {
                $permissions[] = [
                    'permission_id' => $v,
                    'menu_id'       => $AdminMenu->id,
                ];
            }
            $AdminMenu->menuPermission()->delete();
            $data['permissions'] && $AdminMenu->menuPermission()->createMany($permissions);
            Db::commit();
            return $this->rtn_json('', __($data['id'] ? 'messages.admin_common_edit_success' : 'messages.admin_common_add_success', ['name' => __('messages.attributes_admin_menu')]));
        } catch (\Throwable $ex) {
            Db::rollBack();
            return $this->rtn_json($ex->getTraceAsString(), $ex->getMessage(), ErrorCode::REQUEST_TRANSACTION_FAIL);
        }
    }

    public function index()
    {
        $menu_ids = [];
        if (($admin_user_id = $this->session->get(env('ADMIN_USER_LOGIN_KEY'))) > 1) {
            $menu_ids = Db::table('admin_role_menu as a_rm')
                ->leftJoin('admin_role_user as a_ru', 'a_ru.role_id', '=', 'a_rm.role_id')
                ->where([
                    ['a_ru.user_id', '=', $admin_user_id],
                ])
                ->pluck('a_rm.menu_id')
                ->toArray();
        }
        return $this->rtn_json(AdminMenu::getAdminMenuTree(1, $menu_ids));
    }

    public function detail(int $id)
    {
        return $this->rtn_json(AdminMenu::getAdminMenuOne($id));
    }

    public function delete()
    {
        $ids = explode(',', (string)$this->request->input('data.ids', ''));
        if ($ids && AdminMenu::destroy($ids)) {
            return $this->rtn_json('', __('messages.admin_common_delete_success', ['name' => __('messages.attributes_admin_menu')]));
        } else {
            return $this->rtn_json('', __('messages.admin_common_delete_faild', ['name' => __('messages.attributes_admin_menu')]), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }

    public function store(\App\Request\AdminMenuRequest\Store $request)
    {
        return $this->save($request);
    }

    public function update(\App\Request\AdminMenuRequest\Update $request)
    {
        return $this->save($request);
    }


}
