<?php
namespace Sports\Finance\Operate;
use Sports\Utility\Vo\ApiBaseObject;

/**
 * 提供对Account的操作
 * Class PointsService
 * @package Sports\Finance
 */
class ActionObject extends ApiBaseObject
{
    protected $id = null;
    public function setId($iId)
    {
        $this->id = $iId;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }

    protected $operateId = null;
    public function setOperateId($iOperateId)
    {
        $this->operateId = $iOperateId;
        return $this;
    }
    public function getOperateId()
    {
        return $this->operateId;
    }

    protected $userId = null;
    public function setUserId($iUserId)
    {
        $this->userId = $iUserId;
        return $this;
    }
    public function getUserId()
    {
        return $this->userId;
    }

    protected $purpose = null;
    public function setPurpose($ePurpose)
    {
        $this->purpose = $ePurpose;
        return $this;
    }
    public function getPurpose()
    {
        return $this->purpose;
    }

    protected $operateType = null;
    public function setOperateType($eOperate)
    {
        $this->operateType = $eOperate;
        return $this;
    }
    public function getOperateType()
    {
        return $this->operateType;
    }

    protected $amount = null;
    public function setAmount($iNum)
    {
        $this->amount = $iNum;
        return $this;
    }
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @var 获取关联id
     */
    protected $relationId = null;

    /**
     * @param $iRelationId
     * @return $this
     */
    public function setRelationId($iRelationId)
    {
        $this->relationId = $iRelationId;
        return $this;
    }
    public function getRelationId()
    {
        return $this->relationId;
    }

    /**
     * @var 获取关联类型
     */
    protected $relationType = null;

    /**
     * @param $eRelationType
     * @return $this
     */
    public function setRelationType($eRelationType)
    {
        $this->relationType = $eRelationType;
        return $this;
    }
    public function getRelationType()
    {
        return $this->relationType;
    }

    protected $createdTime = null;
    public function getCreatedTime()
    {
        return $this->createdTime;
    }
}
