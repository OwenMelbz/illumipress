<?php

namespace OwenMelbz\IllumiPress;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Cache
 * @package OwenMelbz\IllumiPress
 */
class Cache
{

	protected static $store;

	public function __construct()
	{
		if (!static::$store) {
			$this->setupFileDriver();
		}

		return static::$store;
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

		static::$store = $cacheManager->store();
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
