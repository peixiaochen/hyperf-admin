重启服务建议时间：前端不调用接口的时候
安装说明
参考Api的接口安装说明，后台暂不使用rabbitMq，只用自带的mq即可
###环境依赖
- PHP >= 7.2
- Swoole PHP 扩展 >= 4.4，并关闭 *****Short Name*****
[短域名关闭参考](https://hyperf.wiki/#/zh-cn/quick-start/questions?id=swoole-%e7%9f%ad%e5%90%8d%e6%9c%aa%e5%85%b3%e9%97%ad)
- OpenSSL PHP 扩展
- JSON PHP 扩展
- PDO PHP 扩展 （如需要使用到 MySQL 客户端）
- Redis PHP 扩展 （如需要使用到 Redis 客户端）
- RabbitMq 建议使用docker安装
###git下载应用后直接安装
1. 进入项目后，命令行执行 composer  install
2. 复制一份.env文件  修改.env 的数据库等配置，此文件不可加入git跟踪
3. 修改config/autoload/server.php中
    ```PHP
       'servers'   => [
        [
            'name'      => 'http',
            'type'      => Server::SERVER_HTTP,
            'host'      => '0.0.0.0',
            'port'      => 9520,  //修改此处，端口不要与已经运行的项目重复即可，最好自己选择一个段 例如：91000-91999
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                SwooleEvent::ON_REQUEST => [Hyperf\HttpServer\Server::class, 'onRequest'],
            ],
        ],
       ],
    ```
4. 修改config/autoload/server.php中的一些参数
    [参考链接](https://hyperf.wiki/#/zh-cn/amqp?id=amqp-%e7%bb%84%e4%bb%b6)
5. 执行 php bin/hyperf.php migrate 执行 注：以后添加更改数据库字段都使用migrate，不可在数据库工具使用sql语句添加
    [参考链接](https://hyperf.wiki/#/zh-cn/db/migration)
6. 将根目录的sql文件执行，部署基础数据完成
7. 执行 php bin/hyperf.php start即可进入愉快的开发阶段了

###注意：
- 如果windows系统，推荐使用docker开发，在本项目的文档中有对应的安装方法
- 由于框架是常驻内存，所以每次在调试后需要重启才可生效，在开发接口可以使用 php watch来启动，正式部署，不可这样使用，