<?php
namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\QueryVO;

abstract class StorageAbstract implements Put,Query
{
    /**
     *
     * @param mixed $mConfigVO a ConfigVO or a ConfigVO Array
     * @return mixed|void
     */
    public function put($mConfigVO)
    {
        $aConfigVOArray = array();
        if($mConfigVO instanceof ConfigVO){
            $aConfigVOArray[] = $mConfigVO;
        }else if(is_array($mConfigVO)){
            $aConfigVOArray = $mConfigVO;
        }

        $this->putConfigArray($aConfigVOArray);
    }

    /**
     * @param ConfigVO[] $aConfigVOArray
     */
    abstract protected function putConfigArray(&$aConfigVOArray);

    /**
     * @abstract
     * @param StorageAbstract $oStorage
     * @return mixed
     */
    abstract public function equal($oStorage);

    /**
     * @param ConfigVO $oConfigVO
     */
    public function isConfigEnabled(&$oConfigVO)
    {
        return $oConfigVO->getEnabled() == ConfigVO::ENABLED_TRUE;
    }
}
