<?php
namespace Sports\Object\Finance;
use Sports\Object\Base;

/**
 * Class PointsService
 * @package Finance\Service
 */
class AccountVo extends Base
{
    protected $id = null;
    public function getId()
    {
        return $this->id;
    }

    protected $balance = null;
    public function getBalance()
    {
        return intval($this->balance);
    }

    protected $freeze = null;
    public function getFreeze()
    {
        return $this->freeze;
    }

    protected $credit = null;
    public function getCredit()
    {
        return $this->credit;
    }

    protected $createdTime = null;
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    protected $userId = null;
    public function getUserId()
    {
        return $this->userId;
    }

    protected $purpose = null;
    public function getPurpose()
    {
        return $this->purpose;
    }

    public function getAvailableAmount()
    {
        return $this->getBalance() + $this->getCredit() - $this->getFreeze();
    }
}
