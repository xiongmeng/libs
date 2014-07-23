<?php
namespace Sports\Config\Storage;

use Sports\Config\VO\UniqueVO;
use Sports\Config\VO\ConfigVO;

abstract class StorageKeyValue extends StorageAbstract
{
    private $sJoinString = '/';
    public function getJoinString()
    {
        return $this->sJoinString;
    }
    public function setJoinString($sJoinString)
    {
        $this->sJoinString = $sJoinString;
    }

    /**
     * @param UniqueVO $oUniqueVO
     */
    protected function generateKey($oUniqueVO, $sPrefix='')
    {
        if(!$oUniqueVO instanceof UniqueVO){
            throw new \Exception('$oConfigVO must be instance of ConfigVO');
        }

        $aTmpArray = array($sPrefix,$oUniqueVO->getApp(),$oUniqueVO->getKey(),$oUniqueVO->getExt());
        return join($this->getJoinString(), $aTmpArray);
    }

    /**
     * @param $aKVArray
     * @return ConfigVO[] array
     */
    protected function constructConfigVOByKVArray(&$aKVArray)
    {
        $aResult = array();
        foreach($aKVArray as $sGeneratedKey=>$sValue){
            $oConfigVO = $this->constructConfigVOFromKey($sGeneratedKey);
            $oConfigVO->setValue($sValue);
            $aResult[] = $oConfigVO;
        }
        return $aResult;
    }

    /**
     * @param $sGeneratedKey
     * @return \Sports\Config\VO\ConfigVO
     */
    protected function constructConfigVOFromKey($sGeneratedKey){
        list($sPrefix,$sApp,$sKey,$sExt) = explode($this->getJoinString(),$sGeneratedKey);
        return new ConfigVO($sKey,null,$sApp,$sExt);
    }
}
