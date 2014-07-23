<?php
namespace Sports\ApiClient\Sms;

use Sports\ApiClient\Base;
use Sports\Constant\Sms;
use Sports\Utility\SmsHelper;
use Zend\Db\Adapter\Adapter;

class SendService extends Base
{
    private static $oSelf = null;
    private static function instanceFromConfigSingle()
    {
        if(self::$oSelf === null){
            $aCfg = json_decode(\Sports\Config\ConfigSingle::get('api_finance_ticket'), true);
            $aCfg['host'] = \Sports\Config\ConfigSingle::get('api_sports_host');
            $oService = new SendService($aCfg);

//            $aDsn = \Sports\Config\ConfigSingle::get('db_gotennis_dsn');
//            $oService->setDbAdapter(json_decode($aDsn, true));

            self::$oSelf = $oService;
        }
        return self::$oSelf;
    }

    private $oDbAdapter = null;
    public function setDbAdapter(Adapter $oDbAdapter)
    {
        $this->oDbAdapter = $oDbAdapter;
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
        return SmsHelper::pushQueue($sPhone, $sMeg, $iUserId, $iChannelId);
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
        self::instanceFromConfigSingle()->questApi('/sms/queue/sendLoop', array('limit'=>10), self::METHOD_GET);
    }
}