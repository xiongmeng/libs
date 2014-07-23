<?php
namespace Sports\MetaData;

use Sports\Config\ConfigSingle;

class MetaDataClient
{
    protected static $aDbConfig = array();

    protected static $aRedisConfig = array();

    public static function init($aDbConfig, $aRedisConfig)
    {
        self::$aDbConfig = $aDbConfig;
        self::$aRedisConfig = $aRedisConfig;
    }
}