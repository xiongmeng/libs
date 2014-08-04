<?php
/**
 * 
 */

namespace SmsTest\Service;

use Sports\Constant\Sms;
use Sports\Sms\QueueService;
use Sports\Utility\SmsHelper;

require_once __DIR__ . '/../BaseServiceTest.php';


class QueueServiceTest extends \BaseServiceTest
{
    /**
     * @test
     */
    public function getById()
    {
        $sPhone = '18611367408';
        $sMsg = 'xm_test';
        $iUserId = '1';
        $iId = SmsHelper::pushQueue($sPhone, $sMsg, $iUserId);

        $oQueueService = new QueueService($this->oDbAdapter);
        $oQueueVo = $oQueueService->getById($iId);

        $this->assertEquals($sPhone, $oQueueVo->getPhone());
        $this->assertEquals($sMsg, $oQueueVo->getMessage());
        $this->assertEquals($iUserId, $oQueueVo->getUserId());
        $this->assertEquals(Sms::QUEUE_STATUS_PENDING, $oQueueVo->getStatus());
    }

    /**
     * @test
     */
    public function sendLoop()
    {
//        $oSdkPointsServiceMock = $this->getMockBuilder('Sms\Service\ChannelService')
//            ->disableOriginalConstructor()
//            ->getMock();

//        $oSdkPointsServiceMock->expects($this->any())
//            ->method('sendQueueVo')
//            ->will($this->returnValue(null));

//        $serviceManager = $this->getApplicationServiceLocator();
//        $serviceManager->setAllowOverride(true);
//        $serviceManager->setService(
//            'Sms\Service\ChannelService', $oSdkPointsServiceMock);

        $oQueueService = new QueueService($this->oDbAdapter);
        $aResult = $oQueueService->sendLoop(2);
    }
}