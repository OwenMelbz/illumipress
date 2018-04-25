<?php

namespace OwenMelbz\IllumiPress;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

/**
 * Class Whoops
 * @package OwenMelbz\IllumiPress
 */
class WhoopsManager
{

    protected static $whoops;

    public static function turnOn()
    {
        if (!static::$whoops) {
            static::$whoops = new Run;
            static::$whoops->pushHandler(new PrettyPageHandler);
        }

        static::$whoops->register();

        return static::$whoops;
    }

    public static function turnOff()
    {
        static::$whoops->unregister();

        return static::$whoops;
    }
}
