<?php
namespace Sports\Config\VO;
class ConfigVO extends UniqueVO
{
    const ENABLED_TRUE = 1;
    const ENABLED_FALSE = 2;

    const EXT_DEFAULT = 1;

    const APP_ROOT = 'root';

    private $iId = null;
    public function getId()
    {
        return $this->iId;
    }
    public function setId($iId)
    {
        $this->iId = $iId;
    }

    private $sValue = null;
    public function getValue()
    {
        return $this->sValue;
    }
    public function setValue($sValue)
    {
        $this->sValue= $sValue;
        return $this;
    }

    private $iEnabled = null;
    public function getEnabled()
    {
        return $this->iEnabled;
    }
    public function setEnabled($iEnabled)
    {
        $this->iEnabled= $iEnabled;
        return $this;
    }

    private $sDescription = null;
    public function getDescription()
    {
        return $this->sDescription;
    }
    public function setDescription($sDescription)
    {
        $this->sDescription= $sDescription;
        return $this;
    }


    public function __construct($sKey, $sValue, $sApp = self::APP_ROOT, $sExt = self::EXT_DEFAULT)
    {
        parent::__construct($sKey, $sApp, $sExt);
        $this->setValue($sValue);
        $this->setEnabled(self::ENABLED_TRUE);
    }

    /**
     * @param ConfigVO $oConfigVo
     */
    public function equal($oConfigVo)
    {
        return ($oConfigVo instanceof ConfigVO) && ($this->getKey() == $oConfigVo->getKey()) &&
            ($this->getApp() == $oConfigVo->getApp()) && ($this->getExt() == $oConfigVo->getExt());
    }

    /**
     * 从一个数组创建configVO
     * @static
     * @param $aConfig
     * @return ConfigVO
     */
    public static function constructByArray($aConfig)
    {
        $sKey = '';
        isset($aConfig['key']) && $sKey = $aConfig['key'];
        $sApp = self::APP_ROOT;
        isset($aConfig['app']) && $sApp = $aConfig['app'];
        $sExt = self::EXT_DEFAULT;
        isset($aConfig['ext']) && $sExt = $aConfig['ext'];
        $sValue = '';
        isset($aConfig['value']) && $sValue = $aConfig['value'];

        $oConfig = new ConfigVO($sKey,$sValue,$sApp,$sExt);

        isset($aConfig['enabled']) && $oConfig->setEnabled($aConfig['enabled']);
        isset($aConfig['description']) && $oConfig->setDescription($aConfig['description']);

        return $oConfig;
    }
}
