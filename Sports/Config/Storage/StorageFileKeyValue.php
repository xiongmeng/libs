<?php

namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\QueryVO;

class StorageFileKeyValue extends StorageKeyValue
{
    private $sPath = null;

    public function setPath($sPath)
    {
        $this->sPath = $sPath;
        $this->ensureFileExisted();
    }

    public function getPath()
    {
        return $this->sPath;
    }

    /**
     * @param Array $aConfig array('path'=>'\var\www\temp.ini')
     */
    public function __construct($aConfig = null)
    {
        if (!is_array($aConfig) || !isset($aConfig['path'])) {
            throw  new \Exception('please ensure path is specified in $aConfig');
        }
        $this->setPath($aConfig['path']);
    }

    /**
     * @param ConfigVO[] $aConfigVOArray
     * @return mixed|void
     */
    protected function putConfigArray(&$aConfigVOArray)
    {
        $aKeyValues = $this->read();

        foreach ($aConfigVOArray as $oConfigVO) {
            $sKey = $this->generateKey($oConfigVO);
            $sValue = $oConfigVO->getValue();

            if ($this->isConfigEnabled($oConfigVO)) {
                $aKeyValues[$sKey] = $sValue;
            } else {
                unset($aKeyValues[$sKey]);
            }
        }

        $this->write($aKeyValues);
    }

    /**
     * @param QueryVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function query($oQueryVO = null)
    {
        $aKeyValues = $this->read();
        if (!empty($aKeyValues)) {
            if ($oQueryVO === null) {
                return $this->constructConfigVOByKVArray($aKeyValues);
            } else if ($oQueryVO instanceof \Sports\Config\VO\UniqueVO) {
                $sKey = $this->generateKey($oQueryVO);

                if (isset($aKeyValues[$sKey])) {
                    $aTmpKV = array($sKey =>$aKeyValues[$sKey]);
                    return $this->constructConfigVOByKVArray($aTmpKV);
                }
            }
        }
        return null;
    }

    /**
     * 确保指定的文件存在
     */
    private function ensureFileExisted()
    {
        if (!file_exists($this->sPath)) {
            $sDir = dirname($this->sPath);
            if (!is_dir($sDir)) {
                @mkdir($sDir);
                @chmod($sDir, 777);
            }
            @touch($this->sPath);

            if(!is_file($this->sPath)){
                throw new \Exception('please ensure the file path is valid！');
            }
        }
    }

    /**
     * 将配置信息从文件读取出来
     * @return mixed
     */
    private function read()
    {
        $sJsonContent = file_get_contents($this->sPath);
        $aKeyValues = json_decode($sJsonContent, true);
        return $aKeyValues;
    }

    /**
     * 将指定的配置信息全部写入文件
     * @param $aKeyValues
     */
    private function write(&$aKeyValues)
    {
        $sJsonContent = empty($aKeyValues) ? '' : json_encode($aKeyValues);
        file_put_contents($this->sPath, $sJsonContent);
    }

    /**
     * @param StorageAbstract $oStorage
     * @return boolean
     */
    public function equal($oStorage)
    {
        if ($oStorage instanceof StorageFileKeyValue) {
            return $this->getPath() == $oStorage->getPath();
        }
        return false;
    }
}
