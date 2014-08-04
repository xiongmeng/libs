<?php
/**
 *
 */

namespace Sports\Finance;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class ReportService extends BaseService
{
    public function queryBilling($aQueries, $iPage, $iSize)
    {
        $oSql = new Sql($this->getDbAdapter());

        //查询分页信息
        $oSelect = $oSql->select(array('b' => 'gt_account_billing_staging'));

        $this->buildCondition($oSelect, $aQueries);

        $oSelect->order('b.id desc')->limit($iSize)->offset((max($iPage, 1) - 1) * $iSize);
        $sSqlString = $oSql->getSqlStringForSqlObject($oSelect);

        $oResultSet = new ResultSet(ResultSet::TYPE_ARRAY);
        $oResultSet->initialize($oSql->prepareStatementForSqlObject($oSelect)->execute());
        $aResult = $oResultSet->toArray();

        return $aResult;
    }

    public function queryBillingTotal($aQueries)
    {
        $oSql = new Sql($this->getDbAdapter());

        $oSelect = $oSql->select(array('b' => 'gt_account_billing_staging'));
        $oSelect->columns(array('total' => new Expression('COUNT(1)')));

        $this->buildCondition($oSelect, $aQueries);

        $sSqlString = $oSql->getSqlStringForSqlObject($oSelect);
        $oResultSet = new ResultSet(ResultSet::TYPE_ARRAY);
        $oResultSet->initialize($oSql->prepareStatementForSqlObject($oSelect)->execute());
        $aResult = $oResultSet->toArray();

        return current($aResult)['total'];
    }

    public function queryBillingStatistics($aQueries)
    {
        $oSql = new Sql($this->getDbAdapter());

        $oSelect = $oSql->select(array('b' => 'gt_account_billing_staging'));
        $oSelect->columns(array('relation_type', 'iNumTotal' => new Expression('count(b.id)'),
            'iCostTotal' => new Expression('sum(b.account_change)')))->group('relation_type');

        $this->buildCondition($oSelect, $aQueries);

        $sSqlString = $oSql->getSqlStringForSqlObject($oSelect);
        $oResultSet = new ResultSet(ResultSet::TYPE_ARRAY);
        $oResultSet->initialize($oSql->prepareStatementForSqlObject($oSelect)->execute());
        $aResult = $oResultSet->toArray();
        return $aResult;
    }

    private function buildCondition(Select $oSelect, $aQueries)
    {
        $oWhere = new Where();

        if(isset($aQueries['user_id'])){
            $oWhere->equalTo('b.user_id', $aQueries['user_id']);
        }
        if(isset($aQueries['hall_id'])){
            $oWhere->equalTo('b.hall_id', $aQueries['hall_id']);
        }
        if(isset($aQueries['purpose'])){
            $oWhere->equalTo('b.purpose', $aQueries['purpose']);
        }
        if(isset($aQueries['billing_created_time'])){
            $aValues = explode('-', $aQueries['billing_created_time']);
            !(isset($aValues[0]) && strlen($aValues[0])) && $aValues[0] = 0;
            !(isset($aValues[1]) && strlen($aValues[1])) && $aValues[1] = PHP_INT_MAX;
            $oWhere->between('b.billing_created_time', abs(intval($aValues[0])), abs(intval($aValues[1])));
        }
        if(isset($aQueries['relation_id'])){
            $oWhere->equalTo('relation_id', $aQueries['relation_id']);
        }
        if(isset($aQueries['relation_type'])){
            is_array($aQueries['relation_type']) ?
                $oWhere->in('b.relation_type', $aQueries['relation_type']) :
                $oWhere->equalTo('b.relation_type', $aQueries['relation_type']);
            ;
        }
        if(isset($aQueries['user_name'])){
            $oWhere->like('b.user_name', '%' . $aQueries['user_name'] . '%');
        }

        $oSelect->where($oWhere);
    }
}
