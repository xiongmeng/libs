<?php

namespace Sports\Finance;

use Sports\Utility\ArrayHelper;
use Zend\Db\Adapter\Adapter;
use Sports\Utility\Transaction;

class BaseService
{
    const TYPE_ITEM_REVERSAL_TYPE=99;

    /**
     * @var Transaction
     */
    protected $transaction = null;
    public function __construct(Adapter $oDbAdapter)
    {
        $this->setDbAdapter($oDbAdapter);
        $this->transaction = Transaction::getInstance($oDbAdapter);
    }

    /**
     * @var Adapter
     */
    private $oDbAdapter = null;
    public function setDbAdapter(Adapter $oDbAdapter){
        $this->oDbAdapter = $oDbAdapter;
    }

    /**
     * @return Adapter
     */
    public function getDbAdapter()
    {
        return $this->oDbAdapter;
    }


    /**
     * @param $aArray
     */
    protected function constructVOsFromDbResultArray($aArray, $sVoClassName)
    {
        $aResult = array();
        foreach($aArray as $aData){
            $aResult[] = new $sVoClassName($aData);
        }
        return $aResult;
    }

    /**
     * @param $aDbResultArray array(array('id'=>1), array('id'=>2))
     * @return array
     */
    protected function parseIdsFromDbResultArray($aDbResultArray)
    {
        $aIds = array();
        foreach ($aDbResultArray as $aRow) {
            $aIds[] = current($aRow);
        }

        return $aIds;
    }

    protected function billDataFormat($billData,$dataItems)
    {
        foreach($dataItems as $key=>$val){
            if(isset($val['item'])&&isset($billData[$val['item']])){
                $billData[$key]=$billData[$val['item']];
            }
        }
        $billCreate = ArrayHelper::copyIfExist($billData, array_keys($dataItems));
        return $billCreate;
    }
}
