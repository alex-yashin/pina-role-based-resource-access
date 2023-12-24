<?php

namespace PinaRoleBasedResourceAccess;

use Pina\Access;
use Pina\App;
use Pina\Language;
use Pina\ModuleInterface;
use PinaRoleBasedResourceAccess\SQL\ResourceGateway;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Module implements ModuleInterface
{

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
        $resources = ResourceGateway::instance()->resourceAccesses()->get();
        foreach ($resources as $resource) {
            if (empty($resource['roles'])) {
                continue;
            }
            Access::permit($resource['resource'], $resource['roles']);
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

function __($string)
{
    return Language::translate($string, __NAMESPACE__);
}
