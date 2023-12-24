<?php

namespace PinaRoleBasedResourceAccess\Commands;

use Pina\App;
use Pina\Command;
use Pina\ModuleInterface;
use PinaRoleBasedResourceAccess\Helpers\ResourceNormalizer;
use PinaRoleBasedResourceAccess\SQL\ResourceGateway;
use PinaRoleBasedResourceAccess\SQL\ResourceRoleGateway;
use PinaRoleBasedResourceAccess\SQL\RoleGateway;

class InstallResources extends Command
{

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

    protected function registerResource(string $resource, string $groupCode = '')
    {
        $normalizer = new ResourceNormalizer();
        $resource = $normalizer->normalize($resource);

        $r = ResourceGateway::instance()->whereBy('resource', $resource)->first();
        if (!empty($r)) {
            return;
        }
        $parts = explode('/', $resource);

        if (count($parts) > 1) {
            $parent = ResourceGateway::instance()->whereLike('resource', $parts[0])->first();
            if (!empty($parent)) {
                return;
            }
        }

        // добавляем только новые ресурсы без существующих родительских
        $data = [
            'title' => $title ?? $resource,
            'url' => $resource,
        ];
        $resourceId = ResourceGateway::instance()->insertGetId($data);

        if ($groupCode) {
            $rootRoleId = RoleGateway::instance()->whereBy('code', $groupCode)->id();
            ResourceRoleGateway::instance()->insertIgnore(['resource_id' => $resourceId, 'role_id' => $rootRoleId]);
        }

    }

}