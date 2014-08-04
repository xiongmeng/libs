<?php
/**
 *
 */

namespace Sports\Finance\Operate;

use Sports\Finance\BaseService;
use Sports\Constant\Finance;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;


class ActionService extends BaseService
{
    /**
     * @param ActionObject $oAction
     * @return int
     */
    public function create(ActionObject $oAction){
        $oDbTable = new TableGateway(Finance::TABLE_ACTION, $this->getDbAdapter());
        $oDbTable->insert(array(
            'operate_id' => $oAction->getOperateId(),
            'user_id' => $oAction->getUserId(),
            'purpose' => $oAction->getPurpose(),
            'operate_type' => $oAction->getOperateType(),
            'amount' => $oAction->getAmount(),
            'relation_id' => $oAction->getRelationId(),
            'relation_type' => $oAction->getRelationType(),
            'created_time' => time()
        ));
        return $oDbTable->getLastInsertValue();
    }

    /**
     * @param $iActionId
     * @return ActionObject|null
     */
    public function getById($iActionId)
    {
        $oDbTable = new TableGateway(Finance::TABLE_ACTION,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iActionId);

        $oDbResult = $oDbTable->select($oWhere);
        return $oDbResult->count() > 0 ? new ActionObject($oDbResult->current()) : null;
    }

    /**
     * @param $iOperateId
     * @return ActionObject[]
     */
    public function getActionsByOperateId($iOperateId)
    {
        $oDbTable = new TableGateway(Finance::TABLE_ACTION,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('operate_id', $iOperateId);

        $oDbResult = $oDbTable->select($oWhere);
        return $this->createActionObjectsFromDbResultArray($oDbResult->toArray());
    }

    /**
     * @param ActionObject $oAction
     * @return ActionObject[]
     */
    public function query(ActionObject $oAction)
    {
        $oDbTable = new TableGateway(Finance::TABLE_ACTION,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        if($oAction->getRelationId() !== null){
            $oWhere->equalTo('relation_id', $oAction->getRelationId());
        }
        if($oAction->getRelationType() !== null){
            $oWhere->equalTo('relation_type', $oAction->getRelationType());
        }
        if($oAction->getUserId() !== null){
            $oWhere->equalTo('user_id', $oAction->getUserId());
        }
        if($oAction->getPurpose() !== null){
            $oWhere->equalTo('purpose', $oAction->getPurpose());
        }
        if($oAction->getOperateType() !== null){
            $oWhere->equalTo('operate_type', $oAction->getOperateType());
        }

        $oDbResult = $oDbTable->select($oWhere);
        return $this->createActionObjectsFromDbResultArray($oDbResult->toArray());
    }

    /**
     * @param $aDbResultArray
     * @return ActionObject[]
     */
    private function createActionObjectsFromDbResultArray($aDbResultArray)
    {
        $aResult = array();
        foreach($aDbResultArray as $aDbResult){
            $oAction = new ActionObject($aDbResult);
            $aResult[$oAction->getId()] = $oAction;
        }
        return $aResult;
    }
}
