<?php

namespace OwenMelbz\IllumiPress;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

/**
 * Class WhoopsFactory
 * @package OwenMelbz\IllumiPress
 */
class WhoopsFactory
{

    /**
     * @var \Whoops\Run
     */
    protected static $whoops;

    /**
     * @return \Whoops\Run
     */
    public static function turnOn()
    {
        if (!static::$whoops) {
            static::$whoops = new Run;
            static::$whoops->pushHandler(new PrettyPageHandler);
        }

        static::$whoops->register();

        return static::$whoops;
    }

    /**
     * @return \Whoops\Run
     */
    public static function turnOff()
    {
        static::$whoops->unregister();

        return static::$whoops;
    }
}
