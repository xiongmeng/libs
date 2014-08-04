<?php
namespace Sports\Sms;

use Sports\Constant\Sms;
use Sports\Object\Sms\ChannelVo;
use Sports\Object\Sms\QueueVo;
use Sports\Sms\Channel\BaiWuService;
use Sports\Sms\Channel\EmayService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class ChannelService extends BaseService
{

    /**
     * @param $iChannelId
     * @return null|ChannelVo
     */
    public function getById($iChannelId)
    {
        $oDbTable = new TableGateway(Sms::TABLE_CHANNEL,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iChannelId);

        $oDbResult = $oDbTable->select($oWhere);
        return $oDbResult->count() > 0 ? new ChannelVo($oDbResult->current()) : null;
    }

    private $aChannels = array();

    /**
     * @param $iChannelId
     * @return ChannelVo
     */
    public function getChannelCache($iChannelId)
    {
        if(!isset($this->aChannels[$iChannelId])){
            $this->aChannels[$iChannelId] = $this->getById($iChannelId);
        }

        return $this->aChannels[$iChannelId];
    }

    /**
     * 发送短信
     * @param QueueVo $oQueueVo
     * @return array|string
     */
    public function sendQueueVo(QueueVo $oQueueVo)
    {
        $oChannel = $this->getChannelCache($oQueueVo->getChannelId());

        $oChannelService = $this->channelFactoryByChannelVo($oChannel);

        return $oChannelService->send($oQueueVo->getPhone(), $oQueueVo->getMessage());
    }

    private $oEmayService = null;
    public function getEmayService()
    {
        if($this->oEmayService == null){
            $this->oEmayService = new EmayService();
        }
        return $this->oEmayService;
    }

    private $oBaiWuService = null;
    public function getBaiWuService()
    {
        if($this->oBaiWuService == null){
            $this->oBaiWuService = new BaiWuService(array());
        }
        return $this->oBaiWuService;
    }

    /**
     * @param ChannelVo $oChanelVo
     * @return null|Channel\BaiWuService|EmayService
     */
    private function channelFactoryByChannelVo(ChannelVo $oChanelVo)
    {
        switch($oChanelVo->getId()){
            case Sms::CHANNEL_EMAY:
                $oEmayService = $this->getEmayService();
                $oEmayService->setProxy($oChanelVo->getUrl(), $oChanelVo->getAccount(), $oChanelVo->getPassword());
                return $oEmayService;
            case Sms::CHANNEL_BAIWU:
                $oBaiWuService = $this->getBaiWuService();
                $oBaiWuService->setProxy($oChanelVo->getUrl(), $oChanelVo->getAccount(), $oChanelVo->getPassword(), $oChanelVo->getExt());
                return $oBaiWuService;
                break;
        }
        return NULL;
    }
}
