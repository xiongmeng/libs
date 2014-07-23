<?php
namespace Sports\ZF2Libs\Response\Json;

use \Zend\View\Model\JsonModel;

class DataModel extends JsonModel
{
    public function __construct($iCode, $sMsg, $mData)
    {
        parent::__construct(array('code' => $iCode, 'msg' => $sMsg, 'data'=>$mData));
    }
}