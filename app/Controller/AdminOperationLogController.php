<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AdminOperationLog;

class AdminOperationLogController extends AbstractController
{
    public function index()
    {
        $param = [
            'page'       => $this->request->input('data.page', 1),
            'limit'      => $this->request->input('data.limit', env('PAGE_NUMBER')),
            'keywords'   => $this->request->input('data.keywords', ''),
            'start_time' => $this->request->input('data.start_time', ''),
            'end_time'   => $this->request->input('data.end_time', ''),
        ];
        return $this->rtn_json(AdminOperationLog::getAdminOperationLogList($param));
    }
}
