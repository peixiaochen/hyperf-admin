<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Constants\ErrorCode;
use App\Middleware\BaseMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckLoginMiddleware extends BaseMiddleware
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->has(env('ADMIN_USER_LOGIN_KEY'))) {
            //异步更新登录时间 RabbitMq
            return $handler->handle($request);
        }
        return $this->rtn_json(ErrorCode::LOGIN_FAIL, ErrorCode::getMessage(ErrorCode::LOGIN_FAIL));
    }
}