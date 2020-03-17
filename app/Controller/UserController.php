<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use App\Constants\ErrorCode;

class UserController extends AbstractController
{
    public function detail(int $id)
    {
        return $this->rtn_json(User::getUserOne($id));
    }

    public function index()
    {
        $param = [
            'page'        => $this->request->input('data.page', 1),
            'limit'       => $this->request->input('data.limit', env('PAGE_NUMBER')),
            'keywords'    => $this->request->input('data.keywords', ''),
            'start_time'  => $this->request->input('data.start_time', ''),
            'end_time'    => $this->request->input('data.end_time', ''),
            'user_status' => $this->request->input('data.user_status') >= 0 ? $this->request->input('data.user_status') : -1,
        ];
        return $this->rtn_json(User::getUserList($param));
    }

    public function status(\App\Request\UserRequest\Status $request)
    {
        $data = [
            'ids'    => explode(',', (string)$request->input('data.ids', '')),
            'status' => $request->input('data.status')
        ];
        //状态为0，需要禁止用户的所有提现申请
        if ($data['ids'] && User::query()->whereIn('id', $data['ids'])->update(['status' => $data['status']])) {
            return $this->rtn_json('', __('messages.user_status_set_success'));
        } else {
            return $this->rtn_json('', __('messages.user_status_set_fail'), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }

    public function delete()
    {
        $ids = explode(',', (string)$this->request->input('data.ids', ''));
        if ($ids && User::destroy($ids)) {
            return $this->rtn_json('', __('messages.admin_common_delete_success', ['name' => __('messages.attributes_user')]));
        } else {
            return $this->rtn_json('', __('messages.admin_common_delete_faild', ['name' => __('messages.attributes_user')]), ErrorCode::REQUEST_INVALID_PARAM);
        }
    }
}
