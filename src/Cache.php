<?php

namespace OwenMelbz\IllumiPress;

use Illuminate\Redis\Database;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Cache
 * @package OwenMelbz\IllumiPress
 */
class Cache
{

    protected static $fileDriver;

    protected static $redisDriver;

    protected static $memcachedDriver;

    public function __construct($driver = 'file')
    {

        $storeName = $driver . 'Driver';
        $setupName = 'setup' . ucfirst($driver) . 'Driver';

        if (!static::$$storeName) {
            $this->$setupName();
        }

        return static::$$storeName;
    }

    private function setupFileDriver()
    {
        $container = new Container;

        $container['config'] = [
            'cache.default' => 'file',
            'cache.stores.file' => [
                'driver' => 'file',
                'path' => defined('ILLUMINATE_CACHE') ? ILLUMINATE_CACHE : trailingslashit(wp_upload_dir()['basedir']) . '.cache'
            ]
        ];

        $container['files'] = new Filesystem;

        $cacheManager = new CacheManager($container);

        static::$fileDriver = $cacheManager->store();
    }

    private function setupRedisDriver()
    {
        $container = new Container;

        $container['config'] = [
            'cache.default' => 'redis',
            'cache.stores.redis' => [
                'driver' => 'redis',
                'path' => defined('REDIS_CONNECTION') ? REDIS_CONNECTION : 'default'
            ],
            'cache.prefix' => defined('REDIS_PREFIX') ? REDIS_PREFIX : 'illumipress',
            'database.redis' => [
                'cluster' => false,
                'default' => [
                    'host' => defined('REDIS_HOST') ? REDIS_HOST : '127.0.0.1',
                    'port' => defined('REDIS_PORT') ? REDIS_PORT : '6379',
                    'database' => 0,
                ],
            ]
        ];

        $container['redis'] = new Database($container['config']['database.redis']);

        $cacheManager = new CacheManager($container);

        static::$redisDriver = $cacheManager->store();
    }

    private function setupRedisDriver()
    {
        $container = new Container;

        $container['config'] = [
            'cache.default' => 'memcached',
            'cache.prefix' => defined('MEMCACHED_PREFIX') ? memcached_PREFIX : 'illumipress',
            'cache.stores.memcached' => [
                'driver' => 'memcached',
                'servers' => [
                    [
                        'host' => defined('MEMCACHED_HOST') ? memcached_HOST : '127.0.0.1',
                        'port' => defined('MEMCACHED_PORT') ? memcached_PORT : 11211,
                        'weight' => 100,
                    ]
                ]
            ],
        ];

        $container['memcached'] = new Database($container['config']['database.memcached']);

        $cacheManager = new CacheManager($container);

        static::$redisDriver = $cacheManager->store();
    }

    /**
     * Allows you to easily call any underlying validator methods
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([static::$store, $method], $args);
    }
}
