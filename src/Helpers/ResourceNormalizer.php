<?php

namespace PinaRoleBasedResourceAccess\Helpers;

use Exception;

class ResourceNormalizer
{
    /**
     * Из ресурса формирует шаблон с заменой всех подстановок на :id
     *
     * @param string $resource
     * @return string
     * @throws Exception
     */
    public function normalize(string $resource)
    {
        $trimmed = trim($resource, '/');
        if (empty($trimmed)) {
            return '';
        }
        $parts = explode('/', $trimmed);
        foreach ($parts as $k => $part) {
            if (!isset($part[0])) {
                throw new Exception("Ошибочный шаблон ресурса в Router: " . $resource . ' (REFERER: ' . ($_SERVER['HTTP_REFERER'] ?? '') . ')');
            }
            //чтобы не дублировать разные шаблоны с разными подстановками, все подстановки приводим к :id
            if (isset($part[0]) && $part[0] == ':') {
                $parts[$k] = ':id';
            }
        }
        return implode('/', $parts);
    }
}