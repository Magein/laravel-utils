<?php

namespace Magein\LaravelUtils;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class RedisCache
{
    /**
     * @return Repository
     */
    public static function app(): Repository
    {
        return Cache::store('redis');
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        try {
            $result = self::app()->get($key, $default);
        } catch (InvalidArgumentException $exception) {
            $result = $default;
        }
        return $result;
    }

    /**
     * @param $key
     * @param $value
     * @param null $ttl
     * @return bool
     */
    public static function put($key, $value, $ttl = null): bool
    {
        return self::app()->put($key, $value, $ttl);
    }
}
