<?php

namespace PinaRoleBasedResourceAccess\Commands;

use Pina\App;
use Pina\Command;
use Pina\ModuleInterface;
use PinaRoleBasedResourceAccess\Helpers\ResourceNormalizer;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;
use PinaRoleBasedResourceAccess\SQL\AccessRoleGateway;
use PinaRoleBasedResourceAccess\SQL\RoleGateway;

class InstallResources extends Command
{

    /**
     * @param string $input
     * @throws \Exception
     */
    protected function execute($input = '')
    {
        $groupCode = $input;

        $modules = App::modules();
        $resources = [];
        foreach ($modules as $module) {
            /** @var ModuleInterface $module */
            if (method_exists($module, "initRouter")) {
                $resources = array_merge($resources, $module->initRouter());
            }
        }

        $patterns = App::router()->getPatterns();
        $resources = array_merge($resources, $patterns);
        sort($resources);
        foreach ($resources as $resource) {
            $this->registerResource($resource, $groupCode);
        }

    }

    /**
     * @param string $resource
     * @param string $groupCode
     * @throws \Exception
     */
    protected function registerResource(string $resource, string $groupCode = '')
    {
        $normalizer = new ResourceNormalizer();
        $resource = $normalizer->normalize($resource);

        $r = AccessGateway::instance()->whereBy('resource', $resource)->first();
        if (!empty($r)) {
            return;
        }
        $parts = explode('/', $resource);

        if (count($parts) > 1) {
            $parent = AccessGateway::instance()->whereLike('resource', $parts[0])->first();
            if (!empty($parent)) {
                return;
            }
        }

        // добавляем только новые ресурсы без существующих родительских
        $data = [
            'type' => 'resource',
            'title' => $title ?? $resource,
            'resource' => $resource,
        ];
        $resourceId = AccessGateway::instance()->insertGetId($data);

        if ($groupCode) {
            $rootRoleId = RoleGateway::instance()->whereBy('code', $groupCode)->id();
            AccessRoleGateway::instance()->insertIgnore(['resource_id' => $resourceId, 'role_id' => $rootRoleId]);
        }

    }

}