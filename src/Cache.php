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

    protected static $stores = [
    	'file' => null,
    	'redis' => null,
    	'memcached' => null,
    ];

    protected static $lastAccessedStore;

    public function __construct($driver = 'file')
    {

        $setupName = 'setup' . ucfirst($driver) . 'Driver';

        if (!static::$stores[$driver]) {
            static::$stores[$driver] = $this->$setupName();
        }

        static::$lastAccessedStore = $driver;

        return static::$stores[$driver];
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

        return $cacheManager->store();
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

        return $cacheManager->store();
    }

    private function setupMemcachedDriver()
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

        return $cacheManager->store();
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
        return call_user_func_array([static::$stores[static::$lastAccessedStore], $method], $args);
    }
}
