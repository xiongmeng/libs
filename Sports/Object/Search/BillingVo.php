<?php
namespace Sports\Object\Search;
use Sports\Object\Base;

/**
 * Class BillingVo
 * @package Sports\Object\Search
 */
class BillingVo extends Base
{
    protected $id = null;
    public function getId()
    {
        return $this->id;
    }

    protected $accountId = null;
    public function getAccountId()
    {
        return $this->accountId;
    }

    protected $accountChange = null;
    public function getAccountChange()
    {
        return $this->accountChange;
    }

    protected $accountAfter = null;
    public function getAccountAfter()
    {
        return $this->accountAfter;
    }

    protected $billingCreatedTime = null;
    public function getBillingCreatedTime()
    {
        return $this->billingCreatedTime;
    }

    protected $userId = null;
    public function getUserId()
    {
        return $this->userId;
    }

    protected $userName = null;
    public function getUserName()
    {
        return $this->userName;
    }

    protected $userRealname = null;
    public function getUserRealname()
    {
        return $this->userRealname;
    }

    protected $rechargeToken = null;
    public function getRechargeToken()
    {
        return $this->rechargeToken;
    }

    protected $bookingEventDate = null;
    public function getBookingEventDate()
    {
        return $this->bookingEventDate;
    }

    protected $bookingEndTime = null;
    public function getBookingEndTime()
    {
        return $this->bookingEndTime;
    }

    protected $courtId = null;
    public function getCourtId()
    {
        return $this->courtId;
    }

    protected $bookingCourtNum = null;
    public function getBookingCourtNum()
    {
        return $this->bookingCourtNum;
    }

    protected $bookingCost = null;
    public function getBookingCost()
    {
        return $this->bookingCost;
    }

    protected $courtName = null;
    public function getCourtName()
    {
        return $this->courtName;
    }
}
