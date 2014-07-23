<?php
/**
 * Description
 */

namespace Sports\Config\AppCaller\Adapter;

use Sports\Config\Storage\StorageAbstract;
use Sports\Config\VO\UniqueVO;
use Sports\Config\VO\ConfigVO;

abstract class AdapterAbstract implements AdapterInterface
{
    protected abstract function get($key);

    /**
     * @param StorageAbstract $oStorage
     * @param  UniqueVO $oUniqueVO
     * @return ConfigVO | null
     */
    protected function queryKeyWithHierarchy($oStorage, $oUniqueVO)
    {
        $aConfigVO = $oStorage->query($oUniqueVO);

        if(count($aConfigVO) > 0){
            return current($aConfigVO);
        }

        $aParents = array(ConfigVO::APP_ROOT);
        /**
         * TODO 将来支持 对 config的自动解析
         */
//        preg_match_all('/_/g', $oUniqueVO->getApp(), $aParents);

        foreach($aParents as $sApp){
            $oUniqueVO->setApp($sApp);
            $aConfigVO = $oStorage->query($oUniqueVO);

            if(count($aConfigVO) > 0){
                return current($aConfigVO);
            }
        }

        return null;
    }
}