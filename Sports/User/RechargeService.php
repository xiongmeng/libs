<?php
namespace Sports\User;

use Sports\Constant\User;
use Sports\Object\User\RechargeVo;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class RechargeService extends BaseService
{
    /**
     * @param $iOrderId
     * @return null|RechargeVo
     */
    public function getById($iOrderId)
    {
        $oDbTable = new TableGateway(User::TABLE_RECHARGE,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iOrderId);

        $oDbResult = $oDbTable->select($oWhere);
        return $oDbResult->count() > 0 ? new RechargeVo($oDbResult->current()) : null;
    }


}
