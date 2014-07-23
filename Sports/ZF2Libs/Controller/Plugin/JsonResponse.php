<?php
namespace Sports\ZF2Libs\Controller\Plugin;

use Sports\Interfaces\Data\ISerializable;
use Sports\Exception\ParamsInvalidException;
use \Sports\ZF2Libs\Response\Json\DataModel as JsonDataModel;
use \Sports\Constant\ErrorCode;

use \Zend\Mvc\Controller\Plugin\AbstractPlugin;

class JsonResponse extends AbstractPlugin
{
    public function page()
    {
        return new JsonDataModel(1, 'test', 'test');
    }

    public function success($mData = '', $sMsg = '', $directJson = false)
    {
        if (!$directJson) {
            $mData = $this->makeDataJsonSerializable($mData);
        }
        return new JsonDataModel(ErrorCode::STATUS_SUCCESS, $sMsg, $mData);
    }

    public function error($iCode, $sMsg)
    {
        return new JsonDataModel($iCode, $sMsg, '');
    }

    /**
     * 将数据处理成可以Json序列化的数据
     * @param $mData
     * @return array|bool|float|int|string
     * @throws \Sports\Exception\ParamsInvalidException
     */
    private function makeDataJsonSerializable($mData)
    {
        if(is_object($mData)){
            if ($mData instanceof ISerializable){
                return $mData->toArraySerializable();
            } else {
                throw new ParamsInvalidException("$mData cat not be serializable");
            }
        }else if(is_array($mData)){
            $aResult = array();
            foreach($mData as $sKey => $aData){
                $aResult[$sKey] = $this->makeDataJsonSerializable($aData);
            }
            return $aResult;
        }else {
            return $mData;
        }
    }
}
