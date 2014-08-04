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


class OperateService extends BaseService
{

    /**
     * 记录操作
     * @param OperateObject $oOperate
     * @return int
     */
    public function create(OperateObject $oOperate)
    {
        $oDbTable = new TableGateway(Finance::TABLE_OPERATE, $this->getDbAdapter());
        $oDbTable->insert(array(
            'relation_id' => $oOperate->getRelationId(),
            'relation_type' => $oOperate->getRelationType(),
            'created_time' => time()
        ));
        return $oDbTable->getLastInsertValue();
    }

    /**
     * @param $iOperateId
     * @return OperateObject
     */
    public function getById($iOperateId)
    {
        $oDbTable = new TableGateway(Finance::TABLE_OPERATE,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iOperateId);

        $oDbResult = $oDbTable->select($oWhere);
        return $oDbResult->count() > 0 ? new OperateObject($oDbResult->current()) : null;
    }
}
