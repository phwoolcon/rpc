<?php
$rootPath = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
if (is_file($configFile = $rootPath . '/storage/cache/config-production.php')) {
    $cachedConfig = include $configFile;
    $config = empty($cachedConfig['rpc']['services']['hello-world']['front-config']) ? [] :
        $cachedConfig['rpc']['services']['hello-world']['front-config'];
} else {
    include $rootPath . '/bootstrap/start.php';
    include $rootPath . '/vendor/phwoolcon/di.php';
    $config = Config::get('rpc.services.hello-world.front-config', []);
}
header('content-type: application/javascript');
$content = 'var helloRpcConfig = ' . json_encode($config) . ';';
header('content-length: ' . strlen($content));
echo $content;
