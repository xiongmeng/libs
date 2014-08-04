<?php
namespace Sports\Utility;

use Sports\Config\ConfigSingle;
use Sports\Constant\Sms;
use Sports\Exception\LogicException;
use Sports\Exception\ParamsInvalidException;
use Sports\MetaData\MetaDataClient;
use Sports\Sms\QueueService;
use Zend\Cache\Storage\Adapter\Dba;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class SmsHelper
{
    /**
     * 依赖于MetaData
     * @param $aAccount - array('profit_grade')
     */
    static public function pushQueue($sPhone, $sMsg, $iUserId= null, $iChannelId=Sms::CHANNEL_BAIWU, $iOrder = null)
    {
        if(!class_exists('Sports\Config\ConfigSingle')){
            throw new LogicException('please ensure ConfigSingle is loaded!');
        }

        $aDsn = ConfigSingle::get('db_gotennis_dsn');
        $oDbAdapter = new Adapter(json_decode($aDsn, true));

        $oDbTable = new TableGateway(Sms::TABLE_QUEUE, $oDbAdapter);
        $oDbTable->insert(array(
            'user_id' => $iUserId,
            'channel_id' => $iChannelId,
            'phone' => $sPhone,
            'message' => $sMsg,
            'status' => Sms::QUEUE_STATUS_PENDING,
            'created_time' => time(),
            'order' => time(),
        ));

        return $oDbTable->getLastInsertValue();
    }

    /**
     * 添加到短信队列中
     * @param $sPhone
     * @param $sMeg
     * @param $iUserId
     * @param int $iChannelId
     * @return int
     */
    public static function sendAsync($sPhone, $sMeg, $iUserId, $iChannelId = Sms::CHANNEL_BAIWU)
    {
        return self::pushQueue($sPhone, $sMeg, $iUserId, $iChannelId);
    }

    /**
     * @param $sPhone
     * @param $sMeg
     * @param $iUserId
     * @param int $iChannelId
     */
    public static function sendSync($sPhone, $sMeg, $iUserId, $iChannelId = Sms::CHANNEL_BAIWU)
    {
        SmsHelper::pushQueue($sPhone, $sMeg, $iUserId, $iChannelId);
        $oSendService = new QueueService(DBHelper::masterAdapterFromConfigSingle());
        $oSendService->sendLoop(10);
    }
}