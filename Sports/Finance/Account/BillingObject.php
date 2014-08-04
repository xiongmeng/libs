<?php
namespace Sports\Finance\Account;
use Sports\Utility\Vo\ApiBaseObject;

/**
 * 提供对Account的操作
 * Class PointsService
 * @package Sports\Finance
 */
class BillingObject extends ApiBaseObject
{
    protected $id = null;
    public function getId()
    {
        return $this->id;
    }

    protected $accountId = null;
    public function setAccountId($iAccountId)
    {
        $this->accountId = $iAccountId;
        return $this;
    }
    public function getAccountId()
    {
        return $this->accountId;
    }


    protected $type = null;
    public function setType($iType)
    {
        $this->type = $iType;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }

    protected $actionId = null;
    public function setActionId($iActionId)
    {
        $this->actionId = $iActionId;
        return $this;
    }
    public function getActionId()
    {
        return $this->actionId;
    }

    protected $accountBefore = null;
    public function setAccountBefore($iAccountBefore)
    {
        $this->accountBefore = $iAccountBefore;
        return $this;
    }
    public function getAccountBefore()
    {
        return $this->accountBefore;
    }

    protected $accountChange = null;
    public function setAccountChange($iAccountChange)
    {
        $this->accountChange = $iAccountChange;
        return $this;
    }
    public function getAccountChange()
    {
        return $this->accountChange;
    }

    protected $accountAfter = null;
    public function setAccountAfter($iAccountAfter)
    {
        $this->accountAfter = $iAccountAfter;
        return $this;
    }
    public function getAccountAfter()
    {
        return $this->accountAfter;
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
