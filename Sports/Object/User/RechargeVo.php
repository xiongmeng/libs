<?php
namespace Sports\Object\User;
use Sports\Object\Base;

/**
 * Class PointsService
 * @package Finance\Service
 */
class RechargeVo extends Base
{
    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        isset($data['iToken']) && $this->iToken = $data['iToken'];
        isset($data['sToken']) && $this->sToken = $data['sToken'];
    }

    protected $id = null;
    public function getId()
    {
        return $this->id;
    }

    protected $userId = null;
    public function getUserId()
    {
        return $this->userId;
    }

    protected $money = null;
    public function getMoney()
    {
        return $this->money;
    }

    protected $payMoney = null;
    public function getPayMoney()
    {
        return $this->payMoney;
    }

    protected $type = null;
    public function getType()
    {
        return $this->type;
    }

    protected $createtime = null;
    public function getCreatetime()
    {
        return $this->createtime;
    }

    protected $stat = null;

    public function getStat()
    {
        return $this->stat;
    }

    protected $edittime = null;
    public function getEdittime()
    {
        return $this->edittime;
    }

    protected $orderId = null;
    public function getOrderId()
    {
        return $this->orderId;
    }

    protected $iToken = null;
    public function getIToken()
    {
        return $this->iToken;
    }

    protected $sToken = null;
    public function getSToken()
    {
        return $this->sToken;
    }
}
