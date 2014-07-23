<?php
namespace Sports\Config\Test;

use Sports\Config\Storage\StorageAbstract;
use Sports\Config\VO\ConfigVO;
class ConfigTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param StorageAbstract $oStorage
     * @param ConfigVO $oConfigVO
     */
    protected function assertConfigExistedInStorage($oStorage, $oConfigVO)
    {
        $aCfgQueryArray = $oStorage->query($oConfigVO);
        $this->assertEquals(1,count($aCfgQueryArray));
        $oCfgQueryTmp = $aCfgQueryArray[0];
        $this->assertEquals(true, $oCfgQueryTmp->equal($oConfigVO));
        $this->assertEquals($oCfgQueryTmp->getValue(),$oConfigVO->getValue());
    }
    /**
     * @param StorageAbstract $oStorage
     * @param ConfigVO $oConfigVO
     */
    protected function assertConfigNotExistedInStorage($oStorage, $oConfigVO)
    {
        $aCfgQueryArray = $oStorage->query($oConfigVO);
        $this->assertEquals(0,count($aCfgQueryArray));
    }
}
