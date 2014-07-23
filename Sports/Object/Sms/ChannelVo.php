<?php
namespace Sports\Object\Sms;
use Sports\Object\Base;

/**
 * Class PointsService
 * @package Finance\Service
 */
class ChannelVo extends Base
{
    protected $id = null;
    public function getId()
    {
        return $this->id;
    }

    protected $account = null;
    public function getAccount()
    {
        return $this->account;
    }

    protected $password = null;
    public function getPassword()
    {
        return $this->password;
    }

    protected $stat = null;
    public function getStat()
    {
        return $this->stat;
    }

    protected $name = null;
    public function getName()
    {
        return $this->name;
    }

    protected $desc = null;
    public function getDesc()
    {
        return $this->desc;
    }

    protected $createdTime = null;
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    protected $url = null;
    public function getUrl()
    {
        return $this->url;
    }

    protected $ext = null;
    public function getExt()
    {
        return $this->ext;
    }
}
