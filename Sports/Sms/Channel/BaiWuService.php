<?php
namespace Sports\Sms\Channel;
use Sports\ApiClient\Base;
use Sports\Exception\ParamsInvalidException;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class BaiWuService extends Base
{
    private $sBaseUrl = null;
    private $sCorpId = null;
    private $sCorpPwd = null;
    private $sCorpService = null;

    public function setProxy($sBaseUrl, $sCorpId, $sCorpPwd, $sCorpService)
    {
        $this->sBaseUrl = $sBaseUrl;
        $this->sCorpId = $sCorpId;
        $this->sCorpPwd = $sCorpPwd;
        $this->sCorpService = $sCorpService;
    }

    /**
     * @param $eAction
     * @param array $aParams
     * @param string $sMethod
     * @return array
     * @throws \Sports\Exception\ParamsInvalidException
     * @throws \Exception
     */
    private function curl($eAction, $aParams=array(), $sMethod=self::METHOD_POST)
    {
        if(empty($this->sBaseUrl)|| empty($this->sCorpId) || empty($this->sCorpPwd) || empty($this->sCorpService)){
            throw new ParamsInvalidException('please ensure method setProxy has been called');
        }

        $aAccount = array("corp_id" => $this->sCorpId, "corp_pwd" => $this->sCorpPwd, 'corp_service'=>$this->sCorpService);
        $aParamAll = array_merge($aAccount, $aParams);

        $this->setServerName($this->sBaseUrl);

        $sRes = $this->quest($eAction, $aParamAll, $sMethod);

        //解析返回的xml数据
        if($sRes != 0){
            throw new \Exception($sRes);
        }
        return $sRes;
    }

    /**
     * 发送短信
     * @param type $aSms
     */
    public function send($sPhone, $sMessage, $sCorpMsgId='')
    {
        if(empty($sPhone) || empty($sMessage)){
            return array('error'=>11,'message'=>'手机号或内容为空');
        }

        //该死的百悟要求转码
        $sMessage = iconv("UTF-8", "GBK", $sMessage);

        return $this->curl('/sms_send2.do',
            array("mobile" => $sPhone, 'msg_content' => $sMessage, 'corp_msg_id'=>$sCorpMsgId)
        );
    }
}
