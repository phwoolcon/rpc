<?php

namespace Phwoolcon\Rpc;

use Hprose\Swoole\Http\Server as HttpServer;
use Hprose\Swoole\Server as Server;
use Hprose\Swoole\Socket\Server as SocketServer;
use Hprose\Swoole\WebSocket\Server as WebSocketServer;
use Phwoolcon\Cli\Command;
use Phwoolcon\Config;
use ReflectionProperty;

class Service
{
    /**
     * @var Command
     */
    protected $cli;
    protected $name;
    protected $config = [
        'listen' => 'ws://host:port',
        'procedures' => [],
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
        $methodNamesFunction = new \ReflectionMethod($this->server, 'getDeclaredOnlyInstanceMethods');
        $methodNamesFunction->setAccessible(true);
        $procedures = [];
        foreach ($this->config['procedures'] as $alias => $method) {
            $options = fnGet($method, 'options', []);
            if (isset($method['instance'])) {
                $instance = new $method['instance'];
                $this->server->addInstanceMethods($instance, '', $alias, $options);
                $methodNames = $methodNamesFunction->invoke(null, $method['instance']);
                $existingMethods = fnGet($procedures, $alias, []);
                $procedures[$alias] = array_values(array_unique((array_merge($existingMethods, $methodNames))));
            } elseif (isset($method['function'])) {
                $this->server->addFunction($method['function'], $alias, $options);
                $procedures[] = $alias;
            }
        }
        $this->cli and $this->cli->verbose('Serving procedures: ' . json_encode($procedures, JSON_PRETTY_PRINT));
        $this->server->start();
    }

    public function status()
    {
    }

    public function stop()
    {
    }

    /**
     * @param Command $command
     * @return $this
     */
    public function setCliCommand($command)
    {
        $this->cli = $command;
        return $this;
    }
}
