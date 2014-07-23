<?php
namespace Sports\Object\Sms;
use Sports\Object\Base;

/**
 * Class PointsService
 * @package Finance\Service
 */
class QueueVo extends Base
{
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

    protected $channelId = null;
    public function getChannelId()
    {
        return $this->channelId;
    }

    protected $phone = null;
    public function getPhone()
    {
        return $this->phone;
    }

    protected $message = null;
    public function getMessage()
    {
        return $this->message;
    }

    protected $status = null;
    public function getStatus()
    {
        return $this->status;
    }

    protected $createdTime = null;
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    protected $sendTime = null;
    public function getSendTime()
    {
        return $this->sendTime;
    }

    protected $completedTime = null;
    public function getCompletedTime()
    {
        return $this->completedTime;
    }

    protected $order = null;
    public function getOrder()
    {
        return $this->order;
    }
}
