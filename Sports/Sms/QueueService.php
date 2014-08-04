<?php
namespace Sports\Sms;
use Sports\Constant\Sms;
use Sports\Object\Sms\QueueVo;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class QueueService extends BaseService
{
    private $oChannelService = null;
    public function getChannelService()
    {
        if($this->oChannelService == null){
            $this->oChannelService = new ChannelService($this->getDbAdapter());
        }
        return $this->oChannelService;
    }

    /**
     * @param $iQueueId
     * @return null|QueueVo
     */
    public function getById($iQueueId)
    {
        $oDbTable = new TableGateway(Sms::TABLE_QUEUE,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iQueueId);

        $oDbResult = $oDbTable->select($oWhere);
        return $oDbResult->count() > 0 ? new QueueVo($oDbResult->current()) : null;
    }

    /**
     * @param $iLimit
     * @return array
     */
    public function sendLoop($iLimit)
    {
        $aQueueVoList = $this->loadPendingItems($iLimit);

        $aCompleted = array();
        foreach($aQueueVoList as $oQueueVo){
            $bResult = $this->sendQueueVo($oQueueVo);
            if(!empty($bResult)){
                $aCompleted[] = $oQueueVo->getId();
            }
        }

        return array('pending' => array_keys($aQueueVoList), 'completed' => $aCompleted);
    }

    private function loadPendingItems($iLimit)
    {
        $oDbTable = new TableGateway(Sms::TABLE_QUEUE,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('status', Sms::QUEUE_STATUS_PENDING);

        $oSelect = $oDbTable->getSql()->select();
        $oSelect->where($oWhere)->order('order ASC')->limit($iLimit);

        $oDbResult = $oDbTable->selectWith($oSelect);

        $aQueueVoList = $this->convertDbResultToQueueVoList($oDbResult->toArray());

        return $aQueueVoList;
    }

    public function sendQueueVo(QueueVo $oQueueVo)
    {
        $iQueueId = $oQueueVo->getId();
        $bResult = $this->markSpecifiedItemInSending($iQueueId);
        if($bResult > 0){
            $this->getChannelService()->sendQueueVo($oQueueVo);
            $this->markSpecifiedItemCompleted($iQueueId);
            return $iQueueId;
        }else{
            return false;
        }
    }

    /**
     * @param $aDbResults
     * @return QueueVo[]
     */
    private function convertDbResultToQueueVoList($aDbResults)
    {
        $aVoList = array();
        foreach($aDbResults as $aDbResult){
            $oQueueVo = new QueueVo($aDbResult);
            $aVoList[$oQueueVo->getId()] = $oQueueVo;
         }
        return $aVoList;
    }

    /**
     * 标记指定Item正在发送
     * @param $iQueueId
     */
    public function markSpecifiedItemInSending($iQueueId)
    {
        $oDbTable = new TableGateway(Sms::TABLE_QUEUE,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iQueueId);
        $oWhere->equalTo('status', Sms::QUEUE_STATUS_PENDING);

        return $oDbTable->update(array(
            'status' => Sms::QUEUE_STATUS_SENDING,
            'send_time' => time(),
        ), $oWhere);
    }

    /**
     * 标记制定Item发送完毕
     * @param $iQueueId
     * @return int
     */
    public function markSpecifiedItemCompleted($iQueueId)
    {
        $oDbTable = new TableGateway(Sms::TABLE_QUEUE,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iQueueId);
        $oWhere->equalTo('status', Sms::QUEUE_STATUS_SENDING);

        return $oDbTable->update(array(
            'status' => Sms::QUEUE_STATUS_COMPLETED,
            'completed_time' => time(),
        ), $oWhere);
    }
}
