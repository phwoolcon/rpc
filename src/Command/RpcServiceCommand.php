<?php

namespace Phwoolcon\Rpc\Command;

use Hprose\Swoole\Server;
use Phwoolcon\Cli\Command;

class RpcServiceCommand extends Command
{

    public function fire()
    {
        $server = new Server();
    }
}
