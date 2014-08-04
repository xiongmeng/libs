<?php
namespace Sports\Sms\Channel;
use Sports\Exception\ParamsInvalidException;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class EmayService
{
    private $sBaseUrl = null;
    private $sCdKey = null;
    private $sPassword = null;

    public function setProxy($sBaseUrl, $sCdKey, $sPassword)
    {
        $this->sBaseUrl = $sBaseUrl;
        $this->sCdKey = $sCdKey;
        $this->sPassword = $sPassword;
    }

    /**
     * @param $eAction
     * @param array $aParams
     * @param string $sMethod
     * @return array
     * @throws \Sports\Exception\ParamsInvalidException
     * @throws \Exception
     */
    private function curl($eAction, $aParams=array(), $sMethod='post')
    {
        if(empty($this->sBaseUrl)|| empty($this->sCdKey) || empty($this->sPassword)){
            throw new ParamsInvalidException('please ensure method setProxy has been called');
        }

        $aAccount = array("cdkey" => $this->sCdKey, "password" => $this->sPassword);
        $aParamAll = array_merge($aAccount, $aParams);

        $sUrl = $this->sBaseUrl . $eAction;

        //发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->sBaseUrl . $eAction);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $aParamAll);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //不直接显示数据

        $sXmlRes = curl_exec($ch);
        curl_close($ch);

        //解析返回的xml数据
        preg_match_all("/\<error>(.*?)\<\/error>/", $sXmlRes, $aError);
        preg_match_all("/\<message>(.*?)\<\/message>/", $sXmlRes, $aMessage);
        $aResult = array('error' => -1, 'message' => '未知错误');
        if (is_array($aError) && count($aError) > 1)
            $aResult['error'] = $aError[1][0];
        if (is_array($aMessage) && count($aMessage) > 1)
            $aResult['message'] = $aMessage[1][0];

        if($aResult['error'] != 0){
            throw new \Exception($aResult['message'], $aResult['error']);
        }
        return $aResult;
    }

    /**
     * 序列号注册
     */
    public function regist()
    {
        return $this->curl('regist.action');
    }

    /**
     * 注册公司详细信息
     */
    public function registDetail()
    {
        $aDetailInfo = array(
            "ename" => "美科互动可以有限公司",
            "linkman" => "邓茂虎",
            "phonenum" => "010-88888888",
            "phonenum" => "18688888888",
            "email" => "dengmaohu@micat.com",
            "fax" => "unknown",
            "address" => "北京三元桥国际港",
            "postcode" => "010100",
        );
        return $this->curl('registdetailinfo.action', $aDetailInfo);
    }

    /**
     * 发送短信
     * @param type $aSms
     */
    public function send($sPhone, $sMessage, $sAddserial='')
    {
        if(empty($sPhone) || empty($sMessage)){
            return array('error'=>11,'message'=>'手机号或内容为空');
        }

        return $this->curl('sendsms.action',
            array("phone" => $sPhone, 'message' => $sMessage .'【网球通】' , 'addserial' => $sAddserial)
        );
    }

    /**
     * 查询余额
     * @return type
     */
    public function balance() {
        return $this->curl('querybalance.action', array());
    }
}
