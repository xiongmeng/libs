<?php
namespace Sports\Object\Booking;
use Sports\Object\Base;

/**
 * Class PointsService
 * @package Finance\Service
 */
class OrderVo extends Base
{
    protected $id = null;
    public function getId()
    {
        return $this->id;
    }

    protected $bookId = null;
    public function getBookId()
    {
        return $this->bookId;
    }

    protected $userId = null;
    public function getUserId()
    {
        return $this->userId;
    }

    protected $courtId = null;
    public function getCourId()
    {
        return $this->courtId;
    }

    protected $cardId = null;
    public function getCardId()
    {
        return $this->cardId;
    }

    protected $eventDate = null;
    public function getEventDate()
    {
        return $this->eventDate;
    }

    protected $startTime = null;
    public function getStartTime()
    {
        return $this->startTime;
    }

    protected $endTime = null;
    public function getEndTime()
    {
        return $this->endTime;
    }

    protected $courtNum = null;
    public function getCourtNum()
    {
        return $this->courtNum;
    }

    protected $cost = null;
    public function getCost()
    {
        return $this->cost;
    }

    protected $costText = null;
    public function getCostText()
    {
        return $this->costText;
    }

    protected $stat = null;
    public function getStat()
    {
        return $this->stat;
    }

    protected $createtime = null;
    public function getCreatetime()
    {
        return $this->createtime;
    }

    protected $createuser = null;
    public function getCreateuser()
    {
        return $this->createuser;
    }

    protected $respondent = null;
    public function getRespondent()
    {
        return $this->respondent;
    }

    protected $edittime = null;
    public function getEdittime()
    {
        return $this->edittime;
    }

    protected $paytime = null;
    public function getPaytime()
    {
        return $this->paytime;
    }

    protected $payuser = null;
    public function getPayuser()
    {
        return $this->payuser;
    }
}
