<?php

namespace OwenMelbz\IllumiPress;

use Exception;
use Illuminate\Encryption\Encrypter;

/**
 * Class Encryption
 * @package OwenMelbz\IllumiPress
 */
class Encryption
{

    protected static $encrypter;

    public function __construct($encryptionKey = null)
    {
        if (defined('ILLUMINATE_ENCRYPTION_KEY')) {
            $key = ILLUMINATE_ENCRYPTION_KEY;
        } elseif ($encryptionKey) {
            $key = $encryptionKey;
        } else {
            throw new Exception('Please define the constant ILLUMINATE_ENCRYPTION_KEY to a random 16-character string');
        }

        if (strlen($key) !== 16) {
            throw new Exception('ILLUMINATE_ENCRYPTION_KEY must be only 16-characters long');
        }

        static::$encrypter = new Encrypter($key);

        return static::$encrypter;
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
        return call_user_func_array([static::$encrypter, $method], $args);
    }
}
