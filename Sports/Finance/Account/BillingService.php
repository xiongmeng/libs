<?php
namespace Sports\Finance\Account;
use Sports\Finance\BaseService;

use Sports\Constant\Finance;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

/**
 * 提供对Account的操作
 * Class PointsService
 * @package Sports\Finance
 */
class BillingService extends BaseService
{
    /**
     * 创建关联
     * @param RelationObject $oRelation
     */
    public function create(BillingObject $oBilling)
    {
        $oDbTable = new TableGateway(Finance::TABLE_BILLING, $this->getDbAdapter());

       $oDbTable->insert(array(
           'account_id' => $oBilling->getAccountId(),
           'type' => $oBilling->getType(),
           'action_id' => $oBilling->getActionId(),
           'account_before' => $oBilling->getAccountBefore(),
           'account_change' => $oBilling->getAccountChange(),
           'account_after' => $oBilling->getAccountAfter(),
           'relation_id' => $oBilling->getRelationId(),
           'relation_type' => $oBilling->getRelationType(),
           'created_time' => time()
       ));

        return $oDbTable->getLastInsertValue();
    }

    /**
     * @param $iBillingId
     * @return BillingObject|null
     */
    public function getById($iBillingId)
    {
        $oDbTable = new TableGateway(Finance::TABLE_BILLING,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iBillingId);

        $oDbResult = $oDbTable->select($oWhere);

        return $oDbResult->count() > 0 ? new BillingObject($oDbResult->current()) : null;
    }
}
