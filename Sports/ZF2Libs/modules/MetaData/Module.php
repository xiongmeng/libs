<?php
namespace MetaData;

use Sports\Config\ConfigSingle;
use Sports\MetaData\MetaDataClient;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $aDbConfig = json_decode(ConfigSingle::get('db_gotennis_dsn'), true);

        $aRedisCfgServer = json_decode(ConfigSingle::get('cache_redis_dsn'),true);
        $aRedisCfg = array(
            'adapter' => array(
                'name' => 'Zend\Cache\Storage\Adapter\Redis',
                'options' => array(
                    'server' => $aRedisCfgServer
                )
            )
        );

        MetaDataClient::init($aDbConfig, $aRedisCfg);
    }
}
