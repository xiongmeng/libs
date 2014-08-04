<?php
namespace Sports\Finance\Operate;
use Sports\Utility\Vo\ApiBaseObject;

/**
 * 提供对Account的操作
 * Class PointsService
 * @package Sports\Finance
 */
class OperateObject extends ApiBaseObject
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

    /**
     * @var \Sports\Finance\Operate\ActionObject[]
     */
    protected $actions = array();
    public function addAction(ActionObject $oAction)
    {
        if($oAction->getId() != null){
            $this->actions[$oAction->getId()] = $oAction;
        }else{
            $this->actions[] = $oAction;
        }
    }

    public function getActions()
    {
        return $this->actions;
    }

    protected $createdTime = null;
    public function getCreatedTime()
    {
        return $this->createdTime;
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
}
