<?php

namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\UniqueVO;

class StorageChain extends StorageAbstract
{
    /**
     * @var StorageAbstract[] array
     */
    private $aStorageArray = array();
    public function __construct()
    {
    }

    /**
     * @param $sName
     * @param StorageAbstract $oStorage
     */
    public function appendStorage($sName, $oStorage)
    {
        $this->aStorageArray[$sName] = $oStorage;
    }

    /**
     * @param $sName
     */
    public function removeStorage($sName)
    {
        unset($this->aStorageArray[$sName]);
    }

    /**
     * @param $sName
     * @return null|StorageAbstract
     */
    public function getStorage($sName)
    {
        return isset($this->aStorageArray[$sName]) ? $this->aStorageArray[$sName] : null;
    }

    /**
     * @param ConfigVO[] $mConfigVO
     * @return mixed|void
     */
    protected function putConfigArray(&$aConfigVOArray)
    {
        foreach($this->aStorageArray as $oStorage){
            $oStorage->put($aConfigVOArray);
        }
    }

    /**
     * @param UniqueVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function query($oQueryVO = null)
    {
        $aQueryArray = array();
        $aPreviousStorageArray = array();

        foreach($this->aStorageArray as $oStorage){
            $aQueryArray = $oStorage->query($oQueryVO);
            if(count($aQueryArray) <= 0){
                $aPreviousStorageArray[] = $oStorage;
            }else{
                break;
            }
        }

        foreach($aPreviousStorageArray as $oPreviousStorage){
            $oPreviousStorage->put($aQueryArray);
        }

        return $aQueryArray;
    }

    /**
     * @param StorageAbstract $oStorage
     * @return boolean
     */
    public function equal($oStorage)
    {
        return true;
    }
}
