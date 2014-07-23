<?php
namespace Sports\ApiClient\User;

use Sports\ApiClient\Base;

class Points extends Base
{
    public function refreshRecharge($iRechargeId)
    {
        return $this->questApi('/user/points/refreshRecharge',
            array('recharge_id' => $iRechargeId), self::METHOD_POST);
    }
}