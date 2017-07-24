<?php

namespace Hprose;

/**
 * Class SwooleWebSocketContext
 * This is the IDE helper class for $context variable used in swoole web socket server
 * @package Hprose
 * @see https://github.com/hprose/hprose-php/wiki/06.-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8#passcontext
 */
class SwooleWebSocketContext
{
    public $isMissingMethod = false;

    /**
     * @var \Swoole\WebSocket\Server
     */
    public $server;

    /**
     * Although named as $clients, but this is the $server itself in fact
     * @var \Hprose\Swoole\WebSocket\Server
     */
    public $clients;

    public $fd = 5;
    public $id = "\000\000\000";

    /**
     * @var \stdClass
     * @see https://github.com/hprose/hprose-php/wiki/05.-Hprose-%E5%AE%A2%E6%88%B7%E7%AB%AF#userdata
     */
    public $userdata;

    /**
     * @var \stdClass[] All RPC methods
     */
    public $methods;

    /**
     * @var callable Current calling method
     */
    public $method = ['RpcInstance', 'method'];

    /**
     * @var int
     * @see \Hprose\ResultMode
     * @see https://github.com/hprose/hprose-php/wiki/06.-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8#mode
     */
    public $mode = 0;

    /**
     * @var bool
     * @see https://github.com/hprose/hprose-php/wiki/06.-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8#simple-%E5%B1%9E%E6%80%A7
     */
    public $simple;

    /**
     * @var bool
     * @see https://github.com/hprose/hprose-php/wiki/06.-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8#oneway
     */
    public $oneway = false;

    /**
     * @var bool
     * @see https://github.com/hprose/hprose-php/wiki/06.-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8#async
     */
    public $async = false;

    /**
     * @var bool
     * @see https://github.com/hprose/hprose-php/wiki/06.-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8#passcontext
     */
    public $passContext;

    public $byref = false;
}
