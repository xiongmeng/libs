<?php
/**
 * 
 */

namespace SmsTest\Service\Channel;

use Sports\Constant\Sms;
use Sports\Object\Sms\QueueVo;
use Sports\Sms\ChannelService;
use Sports\User\PointsService;

require_once __DIR__ . '/../BaseServiceTest.php';

class ChannelServiceTest extends \BaseServiceTest
{

    /**
     * @test
     */
    public function getById()
    {
        $oChannelService = new ChannelService($this->oDbAdapter);
        $oChannelVo = $oChannelService->getById(8887);
        print_r($oChannelVo);
    }

    /**
     * @test
     */
    public function sendQueueVo()
    {
        $oQueueVo = new QueueVo(array(
            'id' => 1,
            'user_id' => '1',
            'channel_id' => '8887',
            'phone' => '18611367408',
            'message' => 'xm_test',
            'status' => Sms::QUEUE_STATUS_SENDING,
            'created_time' => time(),
            'send_time' => time(),
            'completed_time' => time(),
            'order' => 1,
        ));

        $oChannelService = new ChannelService($this->oDbAdapter);

        $oChannelVo = $oChannelService->getById($oQueueVo->getChannelId());

        $oSdkPointsServiceMock = $this->getMockBuilder('Sms\Service\Channel\EmayService')
            ->disableOriginalConstructor()
            ->getMock();

        $oSdkPointsServiceMock->expects($this->once())
            ->method('setProxy')
            ->with($this->stringContains($oChannelVo->getUrl()),
                $this->stringContains($oChannelVo->getAccount(), $this->stringContains($oChannelVo->getPassword())))
            ->will($this->returnValue(null));

        $oSdkPointsServiceMock->expects($this->once())
            ->method('send')
            ->with($this->stringContains($oQueueVo->getPhone()), $this->stringContains($oQueueVo->getMessage()))
            ->will($this->returnValue(null));

//        $serviceManager = $this->getApplicationServiceLocator();
//        $serviceManager->setAllowOverride(true);
//        $serviceManager->setService(
//            'Sms\Service\Channel\EmayService', $oSdkPointsServiceMock);

        $oChannelService->sendQueueVo($oQueueVo);
    }

    /**
     * @test
     */
    public function sendQueueVoByBaiWu()
    {
        $oQueueVo = new QueueVo(array(
            'id' => 1,
            'user_id' => '1',
            'channel_id' => '8888',
            'phone' => '18611367408',
            'message' => 'xm_test',
            'status' => Sms::QUEUE_STATUS_SENDING,
            'created_time' => time(),
            'send_time' => time(),
            'completed_time' => time(),
            'order' => 1
        ));

        $oChannelService = new ChannelService($this->oDbAdapter);

        $oChannelVo = $oChannelService->getById($oQueueVo->getChannelId());

        $oSdkPointsServiceMock = $this->getMockBuilder('Sms\Service\Channel\BaiWuService')
            ->disableOriginalConstructor()
            ->getMock();

        $oSdkPointsServiceMock->expects($this->once())
            ->method('setProxy')
            ->with($this->stringContains($oChannelVo->getUrl()),
                $this->stringContains($oChannelVo->getAccount(), $this->stringContains($oChannelVo->getPassword()), $this->stringContains($oChannelVo->getExt())))
            ->will($this->returnValue(null));

        $oSdkPointsServiceMock->expects($this->once())
            ->method('send')
            ->with($this->stringContains($oQueueVo->getPhone()), $this->stringContains($oQueueVo->getMessage()))
            ->will($this->returnValue(null));

//        $serviceManager = $this->getApplicationServiceLocator();
//        $serviceManager->setAllowOverride(true);
//        $serviceManager->setService(
//            'Sms\Service\Channel\BaiWuService', $oSdkPointsServiceMock);

        $oChannelService->sendQueueVo($oQueueVo);
    }
}