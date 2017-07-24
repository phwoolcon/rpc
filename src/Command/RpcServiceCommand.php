<?php

namespace Phwoolcon\Rpc\Command;

use Phwoolcon\Cli\Command;
use Phwoolcon\Config;
use Phwoolcon\Rpc\Service;
use Symfony\Component\Console\Input\InputArgument;

class RpcServiceCommand extends Command
{
    protected $serviceNames;
    protected $outputTimestamp = true;

    protected function configure()
    {
        $this->setDescription('Phwoolcon RPC Service')
            ->addArgument('action', InputArgument::REQUIRED, "Should be one of:
start | stop | stop-all | status | status-all | list")
            ->addArgument('service', InputArgument::OPTIONAL, "RPC Service name");
    }

    protected function getServiceNames()
    {
        if ($this->serviceNames === null) {
            $this->serviceNames = array_keys(Config::get('rpc.services', []));
        }
        return $this->serviceNames;
    }

    public function fire()
    {
        switch (strtolower($this->input->getArgument('action'))) {
            case 'start':
                $service = $this->pickService();
                $this->comment("Starting RPC service {$service->getName()}...");
                $service->start();
                break;
            case 'stop':
                $service = $this->pickService();
                $this->comment("Stopping RPC service {$service->getName()}...");
                $service->stop();
                break;
            case 'stop-all':
                break;
            case 'status':
                $service = $this->pickService();
                $service->status();
                break;
            case 'status-all':
                break;
            case 'list':
                $this->listServices();
                break;
        }
    }

    protected function listServices()
    {
        $this->comment('Available services:');
        $this->comment(implode(PHP_EOL, $this->getServiceNames()));
    }

    protected function pickService()
    {
        if (null === $serviceName = $this->input->getArgument('service')) {
            $services = $this->getServiceNames();
            $serviceName = $this->interactive->choice('Please pick a service', $services);
        } else {
            if (!in_array($serviceName, $this->getServiceNames())) {
                $this->error('Invalid service name ' . $serviceName);
                $this->listServices();
                exit(2);
            }
        }
        $service = new Service($serviceName, $this->di);
        $service->setCliCommand($this);
        return $service;
    }
}
