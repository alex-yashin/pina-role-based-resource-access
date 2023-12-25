<?php


namespace PinaRoleBasedResourceAccess;


class AccessTypeRegistry
{
    protected static $list = [];

    public static function set(string $type, string $title)
    {
        static::$list[$type] = $title;
    }

    public static function get($type): string
    {
        return static::$list[$type] ?? '';
    }

    public static function getVariants()
    {
        $r = [];
        foreach (static::$list as $k => $v) {
            $r[] = ['id' => $k, 'title' => $v];
        }
        return $r;
    }
}