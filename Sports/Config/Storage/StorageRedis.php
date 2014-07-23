<?php

namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\UniqueVO;

require_once __DIR__ . '/../Libs/credis/Client.php';

class StorageRedis extends StorageKeyValue
{
    private $sHost = null;

    public function setHost($sHost)
    {
        $this->sHost = $sHost;
    }

    public function getHost()
    {
        return $this->sHost;
    }

    private $iPort = null;

    public function setPort($iPort)
    {
        $this->iPort = $iPort;
    }

    public function getPort()
    {
        return $this->iPort;
    }

    private $sPrefix = 'cfg';

    public function setPrefix($sPrefix)
    {
        $this->sPrefix = $sPrefix;
    }

    public function getPrefix()
    {
        return $this->sPrefix;
    }

    /**
     * @var \Credis_Client
     */
    private $oRedisClient = null;

    /**
     * @param Array $aConfig array('host'=>'127.0.0.1','port'=>'7369','prefix'=>'cfg')
     */
    public function __construct($aConfig)
    {
        isset($aConfig['host']) && $this->setHost($aConfig['host']);
        isset($aConfig['port']) && $this->setPort($aConfig['port']);
        isset($aConfig['prefix']) && $this->setPrefix($aConfig['prefix']);

        $this->oRedisClient = new \Credis_Client($this->getHost(), $this->getPort());
    }

    /**
     * @param ConfigVO[] $mConfigVO
     * @return mixed|void
     */
    protected function putConfigArray(&$aConfigVOArray)
    {
        $aExpiredKeyArray = array();
        $aEnabledKVArray = array();

        foreach ($aConfigVOArray as $oConfigVO) {
            $sKey = $this->generateKey($oConfigVO, $this->getPrefix());
            $sValue = $oConfigVO->getValue();

            if ($this->isConfigEnabled($oConfigVO)) {
                $aEnabledKVArray[$sKey] = $sValue;
            } else {
                $aExpiredKeyArray[] = $sKey;
            }
        }

        foreach ($aExpiredKeyArray as $sExpiredKey) {
            $this->oRedisClient->expire($sExpiredKey, 0);
        }

        !empty($aEnabledKVArray) && $this->oRedisClient->mSet($aEnabledKVArray);
    }

    /**
     * @param UniqueVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function query($oQueryVO = null)
    {
        $sQueryKey = $this->generateQueryKey($oQueryVO);
        $aKeyArray = $this->oRedisClient->keys($sQueryKey);
        $aValueArray = $this->oRedisClient->mGet($aKeyArray);
        if(!empty($aKeyArray)){
            $aKeyValueArray = array_combine($aKeyArray, $aValueArray);
            if(!empty($aKeyValueArray)){
                return $this->constructConfigVOByKVArray($aKeyValueArray);
            }
        }
        return null;
    }

    /**
     * @param UniqueVO $oQuery
     */
    private function generateQueryKey($oQuery = null)
    {
        $aKeyWordArray = array($this->getPrefix(),'*','*','*');
        if($oQuery instanceof \Sports\Config\VO\UniqueVO){
            $sApp = $oQuery->getApp();
            !empty($sApp) && ($aKeyWordArray[1] = $sApp);

            $sKey = $oQuery->getKey();
            !empty($sKey) && ($aKeyWordArray[2] = $sKey);

            $sExt = $oQuery->getExt();
            !empty($sExt) && ($aKeyWordArray[3] = $sExt);
        }

        return join($this->getJoinString(),$aKeyWordArray);
    }

    /**
     * @param StorageAbstract $oStorage
     * @return boolean
     */
    public function equal($oStorage)
    {
        if ($oStorage instanceof StorageRedis) {
            return ($this->getHost() == $oStorage->getHost()) && ($this->getPort() == $oStorage->getPort());
        }
        return false;
    }
}
