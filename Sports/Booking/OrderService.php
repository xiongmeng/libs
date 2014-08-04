<?php
namespace Sports\Booking;

use Sports\Constant\Booking;
use Sports\Object\Booking\OrderVo;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class OrderService extends BaseService
{
    /**
     * @param $iOrderId
     * @return null|OrderVo
     */
    public function getById($iOrderId)
    {
        $oDbTable = new TableGateway(Booking::TABLE_ORDER,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iOrderId);

        $oDbResult = $oDbTable->select($oWhere);
        return $oDbResult->count() > 0 ? new OrderVo($oDbResult->current()) : null;
    }
}
