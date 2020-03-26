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

use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;

abstract class AbstractController
{
    /**
     * @Inject
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var \Hyperf\HttpServer\Contract\RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var \Hyperf\HttpServer\Contract\ResponseInterface
     */
    protected $response;
    /**
     * @Inject
     * @var \Hyperf\Redis\Redis
     */
    protected $redis;
    /**
     * @Inject
     * @var \Hyperf\Contract\SessionInterface
     */
    protected $session;

    public function rtn_json($data = '', string $msg = '', int $code = ErrorCode::SUCCESS)
    {
        return $this->response->json([
            'code'     => $code,
            'msg'      => $msg ?: ErrorCode::getMessage(ErrorCode::SUCCESS),
            'marktime' => time(),
            'data'     => is_array($data) || $data ? $data : (object)null
        ]);
    }
}
