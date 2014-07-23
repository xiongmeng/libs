<?php
namespace Sports\ApiClient\Finance;

use Sports\ApiClient\Base;
use Sports\Object\Finance\AccountVo;

class Points extends Base
{
    /**
     * @param $iUserId
     * @return AccountVo
     */
    public function getByUserId($iUserId)
    {
        $aData = $this->questApi(
            '/finance/points/getByUserId', array('user_id' => $iUserId), self::METHOD_GET);
        return new AccountVo($aData);
    }

    public function earn($iUserId, $iAmount, $eRelationType, $iRelationId)
    {
        return $this->questApi('/finance/points/earn',
            array(
                'user_id' => $iUserId,
                'amount' => $iAmount,
                'relation_type' => $eRelationType,
                'relation_id' => $iRelationId),
            self::METHOD_POST);
    }

    public function cost($iUserId, $iAmount, $eRelationType, $iRelationId)
    {
        return $this->questApi('/finance/points/cost',
            array(
                'user_id' => $iUserId,
                'amount' => $iAmount,
                'relation_type' => $eRelationType,
                'relation_id' => $iRelationId),
            self::METHOD_POST);
    }

    public function checkIsEarned($iUserId, $eRelationType, $iRelationId)
    {
        return $this->questApi('/finance/points/checkIsEarned',
            array(
                'user_id' => $iUserId,
                'relation_type' => $eRelationType,
                'relation_id' => $iRelationId),
            self::METHOD_GET);
    }

    public function reversalEarned($iUserId, $eRelationType, $iRelationId)
    {
        return $this->questApi('/finance/points/reversalEarned',
            array(
                'user_id' => $iUserId,
                'relation_type' => $eRelationType,
                'relation_id' => $iRelationId),
            self::METHOD_POST);
    }
}