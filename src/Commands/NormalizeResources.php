<?php

namespace PinaRoleBasedResourceAccess\Commands;

use Exception;
use Pina\Command;
use PinaRoleBasedResourceAccess\Helpers\ResourceNormalizer;
use PinaRoleBasedResourceAccess\SQL\AccessGateway;

class NormalizeResources extends Command
{

    /**
     * @param string $input
     * @throws Exception
     */
    protected function execute($input = '')
    {
        $normalizer = new ResourceNormalizer();
        $resources = AccessGateway::instance()->get();
        foreach ($resources as $r) {
            $normalized = $normalizer->normalize($r['resource']);
            if ($normalized != $r['resource']) {
                if (!AccessGateway::instance()->whereBy('resource', $normalized)->exists()) {
                    AccessGateway::instance()->whereId($r['id'])->update(['resource' => $normalized]);
                } else {
                    AccessGateway::instance()->whereId($r['id'])->update(['resource' => '!!!!!!!!!'.$normalized]);
                }
            }
        }
    }
}