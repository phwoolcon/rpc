<?php

namespace Phwoolcon\Rpc\Methods;

use Phwoolcon\Config;

class Hello
{

    public function __construct()
    {
        $jsTestUrl = url('assets/rpc/hello.html');
        $listen = Config::get('rpc.services.hello-world.listen');
        $this->log("Listening {$listen}...");
        $this->log("Please open '{$jsTestUrl}' in your browser for testing.");
    }

    public function test($message = null)
    {
        $this->log('Received ' . var_export(func_get_args(), true));
        return ['hello' => 'Welcome to phwoolcon/rpc, ', 'input' => $message];
    }

    protected function log($message)
    {
        fwrite(STDOUT, date('[Y-m-d H:i:s] ') . $message . PHP_EOL);
    }
}
