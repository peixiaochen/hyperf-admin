<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Session\Handler;

return [
    'handler' => Hyperf\Session\Handler\RedisHandler::class,
    'options' => [
        'connection'      => 'default',
        'path'            => BASE_PATH . '/runtime/session',
        'gc_maxlifetime'  => 99999999999,
        'cookie_lifetime' => 99999999999,
    ],
];
