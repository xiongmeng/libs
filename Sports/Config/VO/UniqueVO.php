<?php
namespace Sports\Config\VO;

/**
 * 提供一个Config的唯一特性
 */
class UniqueVO
{
    private $sApp = null;
    public function getApp()
    {
        return $this->sApp;
    }
    public function setApp($sApp)
    {
        $this->sApp = $sApp;
        return $this;
    }

    private $sKey = null;
    public function getKey()
    {
        return $this->sKey;
    }
    public function setKey($sKey)
    {
        $this->sKey= $sKey;
        return $this;
    }

    private $sExt = null;
    public function getExt()
    {
        return $this->sExt;
    }
    public function setExt($sExt)
    {
        $this->sExt= $sExt;
        return $this;
    }

    public function __construct($sKey, $sApp , $sExt)
    {
        $this->setApp($sApp);
        $this->setKey($sKey);
        $this->setExt($sExt);
    }
}
