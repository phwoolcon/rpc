<?php

namespace Phwoolcon\Rpc\Procedures;

use Hprose\Swoole\Http\Server as HttpServer;
use Hprose\Swoole\Socket\Server as SocketServer;
use Hprose\Swoole\WebSocket\Server as WebSocketServer;
use Phalcon\Di;
use Phwoolcon\Config;
use Phwoolcon\Rpc\Service;

class Hello
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * @var HttpServer|SocketServer|WebSocketServer
     */
    protected $server;

    protected $chatters = [];

    public function __construct()
    {
        $this->service = Di::getDefault()->getShared('current-rpc-service');
        $jsTestUrl = url('assets/rpc/hello.html');
        $listen = Config::get('rpc.services.hello-world.listen');
        $this->log("Listening {$listen}...");
        $this->log("Please open '{$jsTestUrl}' in your browser for testing.");
        $server = $this->server = $this->service->getServer();
        $this->server->publish('chat');
        $server->on('workerStart', function ($swoole) {
            /* @var \Swoole\WebSocket\Server $swoole */
            // Keep alive heartbeat
            $swoole->tick(60 * 1000, function () {
                $this->verbose('Keep alive heartbeat');
                $this->server->push('chat', '');
            });
        });
        $server->onSubscribe = function ($topic, $id, \Hprose\Service $service) {
            $this->log($message = sprintf('%s has joint the chatting room.', $id));
            $this->chatters[$id] = $id;
            $this->server->multicast($topic, array_keys($this->chatters), [
                'type' => 'join',
                'time' => time(),
                'message' => $message,
            ]);
        };
        $server->onUnsubscribe = function ($topic, $id, \Hprose\Service $service) {
            $this->log($message = sprintf('%s has left the chatting room.', $this->chatters[$id]));
            unset($this->chatters[$id]);
            $this->server->multicast($topic, array_keys($this->chatters), [
                'type' => 'leave',
                'time' => time(),
                'message' => $message,
            ]);
        };
    }

    public function test($id, $message = null)
    {
        $arguments = func_get_args();
        /* @var \Hprose\SwooleWebSocketContext $context */
        $context = array_pop($arguments);
        $this->verbose('from ' . $id);
        $message = htmlentities($message);
        $this->server->multicast('chat', $this->chatters, [
            'type' => 'chat',
            'time' => time(),
            'message' => $message,
            'from_id' => $id,
            'from_name' => $id,
        ]);
        $this->verbose('Received ' . var_export($arguments, true));
        return true;
    }

    protected function log($message)
    {
        $this->service->getCliCommand()->info($message);
    }

    protected function verbose($message)
    {
        $this->service->getCliCommand()->verbose($message);
    }
}
