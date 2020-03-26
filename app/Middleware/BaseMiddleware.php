<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;

abstract class BaseMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function rtn_json(int $code = ErrorCode::SUCCESS, string $msg = '', $data = '')
    {
        return $this->response->json([
            'code'     => $code,
            'msg'      => $msg ?: ErrorCode::getMessage(ErrorCode::SUCCESS),
            'marktime' => time(),
            'data'     => is_array($data) || $data ? $data : (object)null
        ]);
    }
}