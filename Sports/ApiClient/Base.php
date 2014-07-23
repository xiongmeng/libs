<?php
namespace Sports\ApiClient;

use Sports\Constant\ErrorCode;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Uri\Http;

class Base
{
    private $_appId  = NULL;
    private $_appKey = NULL;
    private $_serverName = NULL;
    private $_format = 'json';

    const METHOD_POST = 'POST';
    const METHOD_GET  = 'GET';

    const PROTOCOL_HTTP = 'http';
    const PROTOCOL_HTTPS = 'https';

    /**
     * 构造函数
     * @param int $appId
     * @param string $appKey
     */
    function __construct($aCfg) {
        $this->_appId = isset($aCfg['app_id']) ? $aCfg['app_id'] : "";
        $this->_appKey = isset($aCfg['app_key']) ? $aCfg['app_key'] : "";
        $host = isset($aCfg['host']) ? $aCfg['host'] : "";
        $this->setServerName($host);
    }

    /** */
    public function setServerName($serverName) {
        $this->_serverName = $serverName;
    }

    /**
     * 执行API调用，返回结果数组
     *
     * @param string $script_name 调用的API方法
     * @param array $params 调用API时带的参数
     * @param string $method 请求方法 post / get
     * @param string $protocol 协议类型 http / https
     * @return array 结果数组
     */
    public function questApi($apiName, $params, $method = self::METHOD_POST, $protocol = self::PROTOCOL_HTTP) {
        // 无需传sign, 会自动生成
        unset($params['sign']);

        // 添加一些参数
        $params['appId'] = $this->_appId;
        $params['format'] = $this->_format;
        $params['ts'] = time();

        $oUri = new Http();
        $oUri->setScheme($protocol);
        $oUri->setHost($this->_serverName);
        $oUri->setPath($apiName);

        // 发起请求
        $sResult = $this->request($oUri, $params, $method);

        $aResultArray = json_decode($sResult, true);

        // 远程返回的不是 json 格式, 说明返回包有问题
        if (is_null($aResultArray)) {
            throw new \Exception('the response is invalid');
        }

        if ($aResultArray['code'] == ErrorCode::STATUS_SUCCESS) {
            return $aResultArray['data'];
        }else{
            throw new \Exception($aResultArray['msg'], $aResultArray['code']);
        }
    }

    public function quest($apiName, $params, $method = self::METHOD_POST, $protocol = self::PROTOCOL_HTTP)
    {
        $oUri = new Http();
        $oUri->setScheme($protocol);
        $oUri->setHost($this->_serverName);
        $oUri->setPath($apiName);

        // 发起请求
        return $this->request($oUri, $params, $method);
    }

    private function request($url, $params, $method = self::METHOD_POST)
    {
        $oRequest = new Request();
        $oRequest->setUri($url);
        $oRequest->setMethod($method);

        if($method == self::METHOD_GET){
            $oRequest->getQuery()->fromArray($params);
        }else if($method == self::METHOD_POST){
            $oRequest->getPost()->fromArray($params);
        }

        $oClient = new Client();
        $oClient->setOptions(array(
            'adapter'      => 'Zend\Http\Client\Adapter\Curl',
            'maxredirects' => 0,
            'timeout'=> 30
        ));
        $oClient->setEncType(Client::ENC_URLENCODED);
        $oResponse = $oClient->dispatch($oRequest);

        if($oResponse->getStatusCode() != Response::STATUS_CODE_200){
            throw new \Exception('access api error', $oResponse->getStatusCode());
        }

        return $oResponse->getContent();
    }

    static public function makeQueryString($params)
    {
        if (is_string($params))
            return $params;

        $queryString = array();
        foreach ($params as $key => $value) {
            array_push($queryString, rawurlencode($key) . '=' . rawurlencode($value));
        }
        $queryString = join('&', $queryString);
        return $queryString;
    }

}