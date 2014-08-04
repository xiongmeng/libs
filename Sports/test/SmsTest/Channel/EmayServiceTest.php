<?php
/**
 * 
 */

namespace SmsTest\Service\Channel;


use Sports\Sms\Channel\EmayService;

class PointsServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function send()
    {
        $oEmayService = new EmayService();
        $oEmayService->setProxy('http://sdkhttp.eucp.b2m.cn/sdkproxy/', $sCdKey ,$sPassword);
//        $aResult = $oEmayService->send('18611367408', 'xm_test');
//        print_r($aResult);
    }
}