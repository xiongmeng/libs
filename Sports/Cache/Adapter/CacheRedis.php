<?php
namespace Sports\Cache\Adapter;

use Sports\Cache\CacheInterface;

class CacheRedis implements CacheInterface
{
    /**
     * @var Redis
     */
    private $oRedis = null;

    /**
     * @var string
     */
    private $sHost = '';

    /**
     * @var int
     */
    private $iPort = 0;

    /**
     * @param array $aConfig array('host'=>xxx,'port'=>yyy)
     */
    public function __construct($aConfig)
    {
        if (!extension_loaded('redis')) {
            throw new \Exception('please ensure redis extension is loaded in php.ini');
        }

        isset($aConfig['host']) && $this->sHost = $aConfig['host'];
        isset($aConfig['port']) && $this->iPort = $aConfig['port'];

        if (empty($this->sHost) || empty($this->iPort)) {
            throw new \Exception('please ensure host and port is set in $aCondig');
        }

        $this->oRedis = new \Redis();
        $this->oRedis->connect($this->sHost, $this->iPort);
    }

    /**
     * @param string $sKey
     * @param mixed $mValue
     * @param int $sExpire
     */
    public function set($sKey, $mValue, $iExpire = null)
    {
        if($this->oRedis){
            $this->oRedis->set($sKey, $mValue);
            !empty($iExpire) && $this->oRedis->expire($iExpire);
        }
    }

    /**
     * @param $sKey
     */
    public function get($sKey)
    {
        $sValue = $this->oRedis->get($sKey);
        return $sValue;
    }

}