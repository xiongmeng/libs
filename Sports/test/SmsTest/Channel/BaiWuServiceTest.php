<?php
/**
 * 
 */

namespace SmsTest\Service\Channel;

use Sports\Sms\Channel\BaiWuService;

class BaiWuServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function send()
    {
        $oEmayService = new BaiWuService(array());
        $oEmayService->setProxy('118.144.76.45:8080', '2dc2005' ,'2dc2005', '10655059yd');
        $aResult = $oEmayService->send('18611367408', 'xm_test');
        print_r($aResult);
    }
}