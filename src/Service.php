<?php

namespace Phwoolcon\Rpc;

use Exception;
use Hprose\Swoole\Http\Server as HttpServer;
use Hprose\Swoole\Server as Server;
use Hprose\Swoole\Socket\Server as SocketServer;
use Hprose\Swoole\WebSocket\Server as WebSocketServer;
use Phwoolcon\Config;
use ReflectionProperty;

class Service
{
    protected $name;
    protected $config = [
        'listen' => 'ws://host:port',
        'methods' => [],
    ];

    /**
     * @var HttpServer|SocketServer|WebSocketServer
     */
    protected $server;

    public function __construct($name)
    {
        $this->name = $name;
        $this->config = Config::get('rpc.services.' . $name);
    }

    /**
     * @param string $listen
     * @return HttpServer|SocketServer|WebSocketServer
     */
    protected function createHproseServer($listen)
    {
        $server = new Server($listen);
        $realServerProperty = new ReflectionProperty(Server::class, 'server');
        $realServerProperty->setAccessible(true);
        return $realServerProperty->getValue($server);
    }

    public function getName()
    {
        return $this->name;
    }

    public function start()
    {
        $this->server = $this->createHproseServer($this->config['listen']);
        foreach ($this->config['methods'] as $alias => $method) {
            $options = fnGet($method, 'options', []);
            if (isset($method['instance'])) {
                $instance = new $method['instance'];
                $this->server->addInstanceMethods($instance, '', $alias, $options);
            } elseif (isset($method['function'])) {
                $this->server->addFunction($method['function'], $alias, $options);
            }
        }
        $this->server->start();
    }

    public function status()
    {
    }

    public function stop()
    {
    }
}
