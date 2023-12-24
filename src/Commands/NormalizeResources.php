<?php

namespace PinaRoleBasedResourceAccess\Commands;

use Pina\Command;
use PinaRoleBasedResourceAccess\Helpers\ResourceNormalizer;
use PinaRoleBasedResourceAccess\SQL\ResourceGateway;

class NormalizeResources extends Command
{

    protected function execute($input = '')
    {
        $normalizer = new ResourceNormalizer();
        $resources = ResourceGateway::instance()->get();
        foreach ($resources as $r) {
            $normalized = $normalizer->normalize($r['resource']);
            if ($normalized != $r['resource']) {
                if (!ResourceGateway::instance()->whereBy('resource', $normalized)->exists()) {
                    ResourceGateway::instance()->whereId($r['id'])->update(['resource' => $normalized]);
                } else {
                    ResourceGateway::instance()->whereId($r['id'])->update(['resource' => '!!!!!!!!!'.$normalized]);
                }
            }
        }
    }
}