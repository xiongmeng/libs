<?php
/**
 * Description
 */

namespace Sports\Config;

use Sports\Config\AppCaller\Adapter\AdapterAbstract;
use Sports\Config\AppCaller\ConfigFactory;

class ConfigSingle
{
    private static $adapter;
    public static function init($adapterName, $configData){
        self::$adapter = ConfigFactory::factory($adapterName, $configData);
    }

    public static function get($key){
        if (!self::$adapter instanceof AdapterAbstract) {
            throw new \Exception("config adapter is not exists!");
        } else {
            return self::$adapter->get($key);
        }
    }
}