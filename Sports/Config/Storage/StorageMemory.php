<?php

namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\UniqueVO;

use Sports\Cache\Adapter\CacheMemory;

class StorageMemory extends StorageKeyValue
{
    private $iUpperBound = 500;

    /**
     * @var CacheMemory
     */
    private $oMemoryCache = null;

    /**
     * @param Array $aConfig array('upper_bound'=>500)
     */
    public function __construct($aConfig = null)
    {
        if (is_array($aConfig)) {
            isset($aConfig['upper_bound']) && $this->iUpperBound = $aConfig['upper_bound'];
        }

        $this->oMemoryCache = new CacheMemory($this->iUpperBound);
    }

    /**
     * @param ConfigVO[] $mConfigVO
     * @return mixed|void
     */
    protected function putConfigArray(&$aConfigVOArray)
    {
        foreach ($aConfigVOArray as $oConfigVO) {
            $sKey = $this->generateKey($oConfigVO);
            $sValue = $oConfigVO->getValue();

            if ($this->isConfigEnabled($oConfigVO)) {
                $this->oMemoryCache->set($sKey, $sValue);
            } else {
                $this->oMemoryCache->set($sKey, $sValue, -1);
            }
        }
    }

    /**
     * @param UniqueVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function query($oQueryVO = null)
    {
        if ($oQueryVO === null) {
            $aKeyValues = $this->oMemoryCache->getAll();
            if (!empty($aKeyValues)) {
                return $this->constructConfigVOByKVArray($aKeyValues);
            }
        } else if ($oQueryVO instanceof \Sports\Config\VO\UniqueVO) {
            $sKey = $this->generateKey($oQueryVO);
            $sValue = $this->oMemoryCache->get($sKey);
            if (!empty($sValue)) {
                $aKeyValues = array($sKey => $sValue);
                return $this->constructConfigVOByKVArray($aKeyValues);
            }
        }
        return null;
    }

    /**
     * @param StorageAbstract $oStorage
     * @return boolean
     */
    public function equal($oStorage)
    {
        return $this === $oStorage;
    }
}
