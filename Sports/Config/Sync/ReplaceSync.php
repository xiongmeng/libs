<?php
namespace Sports\Config\Sync;

use Sports\Config\Storage\StorageAbstract;
use Sports\Config\VO\ConfigVO;

class ReplaceSync implements Sync
{
    /**
     * @param StorageAbstract $oSource
     * @param StorageAbstract $oDestination
     * @return mixed|void
     */
    public function sync($oSource, $oDestination)
    {
        if(!($oSource instanceof StorageAbstract) || !($oDestination instanceof StorageAbstract)){
            throw new \Exception('$oSource and $oDestination must be an instance of StorageAbstract');
        }
        if($oSource->equal($oDestination)){
            return ;
        }

        $aSrcCfgArray = $oSource->query();
        empty($aSrcCfgArray) && $aSrcCfgArray = array();
        $aDesCfgArray = $oDestination->query();
        empty($aDesCfgArray) && $aDesCfgArray = array();
        /**
         * TODO load出来的个数进行限制
         */

        //将destination的config为失效
        foreach($aDesCfgArray as $oDesCfg){
            $oDesCfg->setEnabled(ConfigVO::ENABLED_FALSE);
        }

        //将destination中merge至source
        $aResultCfg = $aSrcCfgArray;
        foreach($aDesCfgArray as $oDesCfg){
            if(!$this->isConfigExistedInSpecifiedArray($aSrcCfgArray,$oDesCfg)){
                $aResultCfg[] = $oDesCfg;
            }
        }

        //将merge的集合put至destination中
        $oDestination->put($aResultCfg);
    }

    /**
     * @param ConfigVO[] $aSrcArray
     * @param ConfigVO $oConfigVo
     */
    private function isConfigExistedInSpecifiedArray(&$aSrcArray, $oConfigVo)
    {
        foreach($aSrcArray as $oSrcCfg){
            if($oConfigVo->equal($oSrcCfg)){
                return true;
            }
        }
        return false;
    }
}
