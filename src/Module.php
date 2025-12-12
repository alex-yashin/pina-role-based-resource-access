<?php

namespace PinaRoleBasedResourceAccess;

use Pina\App;
use Pina\ModuleInterface;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function Pina\__;

class Module implements ModuleInterface
{

    public function __construct()
    {
        AccessTypeRegistry::set('resource', __('Разделы'));
    }

    public function getPath()
    {
        return __DIR__;
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getTitle()
    {
        return 'Role Based Resource Access';
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function http(): array
    {
        $resources = AccessGateway::instance()
            ->whereBy('type', 'resource')
            ->resourceAccesses()
            ->get();

        foreach ($resources as $resource) {
            if (empty($resource['roles'])) {
                continue;
            }
            App::access()->permit($resource['resource'], $resource['roles']);
        }

        return $this->initRouter();
    }

    public function cli()
    {
    }

    public function initRouter()
    {
        return [];
    }
}
