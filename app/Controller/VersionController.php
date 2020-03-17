<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Version;
use Hyperf\DbConnection\Db;
use App\Constants\ErrorCode;

class VersionController extends AbstractController
{

    public function save($request)
    {
        $data = [
            'id'             => $request->input('data.id', null),
            'dpt'            => $request->input('data.dpt'),
            'version'        => $request->input('data.version'),
            'version_tip'    => $request->input('data.version_tip'),
            'min_version_id' => $request->input('data.min_version_id'),
            'apk_url'        => $request->input('data.apk_url'),
            'description'    => $request->input('data.description'),
            'level'          => $request->input('data.level'),
            'status'         => (int)$request->input('data.status', 1) ? 1 : 0,
            'extra'          => json_encode([
                'admin_user_id' => $this->session->get(env('ADMIN_USER_LOGIN_KEY'))
            ])
        ];
        Db::beginTransaction();
        try {
            $Version = Version::updateOrCreate([
                'id' => $data['id'],
            ], $data);
            if (!$Version) {
                throw new \Exception(__($data['id'] ? 'messages.admin_common_edit_fail' : 'messages.admin_common_add_fail', ['name' => __('messages.attributes_version')]));
            }
            Db::commit();
            return $this->rtn_json('', __($data['id'] ? 'messages.admin_common_edit_success' : 'messages.admin_common_add_success', ['name' => __('messages.attributes_version')]));
        } catch (\Throwable $ex) {
            Db::rollBack();
            return $this->rtn_json($ex->getTraceAsString(), $ex->getMessage(), ErrorCode::REQUEST_TRANSACTION_FAIL);
        }
    }

    public function store(\App\Request\VersionRequest\Store $request)
    {
        return $this->save($request);
    }

    public function update(\App\Request\VersionRequest\Update $request)
    {
        return $this->save($request);
    }

    public function index()
    {
        $param = [
            'type'       => (int)$this->request->input('data.type', 0),
            'page'       => $this->request->input('data.page', 1),
            'limit'      => $this->request->input('data.limit', env('PAGE_NUMBER')),
            'keywords'   => addslashes($this->request->input('data.keywords', '')),
            'start_time' => $this->request->input('data.start_time', ''),
            'end_time'   => $this->request->input('data.end_time', ''),
        ];
        return $this->rtn_json(Version::getVersionList($param));
    }
}
