<?php
/**
 * 基础model
 */
namespace ApiUtility\Model;

use Utility\Helper\BatchInsert;
use Utility\Vo\ApiBaseVo;
use Zend\Db\ResultSet\ResultSet;
use \Utility\Vo\JoinVo;
use Zend\Db\Adapter\Adapter as DbAdapter;

class ApiBaseModel
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter = NULL;
    protected $ServiceManagers = NULL;

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function __construct(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $this->ServiceManagers = $serviceManager;
        $this->dbAdapter= $this->getServiceManager()->get('Zend\Db\Adapter\Adapter');
    }

    protected function getServiceManager()
    {
        return $this->ServiceManagers;
    }

    protected function fetchCol($column, $table, $where, $data = array())
    {
        $data_array = array();
        $data_array['columns'] = array($column);
        $data_array = array_merge($data_array, $data);
        $results = $this->fetchAll($table, $where, $data_array)->toArray();
        $returnArray = array();
        if (!empty($results)) {
            foreach ($results as $key => $val) {
                $returnArray[$key] = $val[$column];
            }
        }
        return $returnArray;
    }

    protected function fetchCount($table, $where)
    {
        $count = 0;
        $data = array();
        $data['columns'] = array("count" => new \Zend\Db\Sql\Expression("count(*)"));
        $data['limit'] = 1;
        $results = $this->fetchAll($table, $where, $data)->toArray();
        if (!empty($results)) {
            $result = current($results);
            $count = $result['count'];
        }
        return $count;
    }

    /**
     * @param $table
     * @param $where
     * @param array $data
     * @param array $joins
     * @return ResultSet
     */

    protected function fetchAll($table, $where, $data = array(), $joins = array())
    {
        $sql = new \Zend\Db\Sql\Sql($this->dbAdapter);
        $select = $sql->select();
        if (isset($data['columns']) && !empty($data['columns'])) {
            $select->columns($data['columns']);
        }

        $select->from($table);

        foreach ($joins as $val) {
            if (!$val instanceof JoinVo) {
                continue;
            }
            $select->join($val->getTable(), $val->getOn(), $val->getColumns(), $val->getJoinType());
        }

        $select->where($where);

        if (isset($data['offset']) && !empty($data['offset'])) {
            $select->offset((int)$data['offset']);
        }
        if (isset($data['limit']) && !empty($data['limit'])) {
            $select->limit((int)$data['limit']);
        }

        if (isset($data['groupBy']) && !empty($data['groupBy'])) {
            $select->group($data['groupBy']);
        }

        if (isset($data['orderBy']) && !empty($data['orderBy'])) {
            $select->order($data['orderBy']);
        }

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->dbAdapter->query($selectString, dbAdapter::QUERY_MODE_EXECUTE);
        return $results;
    }

    protected function fetchRow($table, $where, $data = array())
    {
        $sql = new \Zend\Db\Sql\Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from($table);
        $select->where($where);
        $select->limit(1);
        $statement = $this->dbAdapter->query($sql->getSqlStringForSqlObject($select));
        $results = $statement->execute();
        return $results;
    }

    /**
     * 插入方法，如果没有自增主键则返回受影响行数
     * @param $table
     * @param array $data
     * @return int
     */
    protected function insert($table, array $data)
    {
        if (empty($data)) {
            return false;
        }
        $insert = new \Zend\Db\Sql\Insert($table);
        $columns = array_keys($data);
        $insert->columns($columns);
        $insert->values($data, $insert::VALUES_SET);
        $result = $this->dbAdapter->query($insert->getSqlString($this->dbAdapter->getPlatform()),
            \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $return = $result->getGeneratedValue();
        if (empty($return)) {
            $return = $result->getAffectedRows();
        }
        return $return;
    }

    /**
     * 插入方法，如果没有自增主键则返回受影响行数
     * @param $table
     * @param array $columns
     * @param array $data
     * @return int
     */
    protected function batchInsert($table, array $columns, array $data)
    {
        $insert = new BatchInsert($table);
        $insert->columns($columns);
        $insert->values($data);
        $result = $this->dbAdapter->query($insert->getSqlString($this->dbAdapter->getPlatform()),
            \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $return = $result->getGeneratedValue();
        if (empty($return)) {
            $return = $result->getAffectedRows();
        }
        return $return;
    }

    /**
     *  修改方法，返回受影响行数
     * @param $table
     * @param array $data
     * @param array $where
     * @return bool
     */
    protected function update($table, array $data, array $where = array())
    {
        if (empty($data)) {
            return false;
        }
        $update = new \Zend\Db\Sql\Update($table);
        $update->set($data);
        $update->where($where);
        $result = $this->dbAdapter->query($update->getSqlString($this->dbAdapter->getPlatform()),
            \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        return $result->getAffectedRows();
    }

    /**
     * @param $aResult
     * @param $oVoName
     * @param string $primaryKey
     * @return \selfMedia\Common\BaseVo[]
     */
    protected function resultBindForList($aResult, $oVoName, $primaryKey = "")
    {

        $returnArray = array();
        if (!class_exists($oVoName)) {
            return $returnArray;
        }

        $resultSetPrototype = new ResultSet();
        $array = $resultSetPrototype->initialize($aResult)->toArray();

        foreach ($array as $val) {
            $oVo = new $oVoName($val);
            if ($primaryKey != "" && isset($val[$primaryKey])) {
                $returnArray[$val[$primaryKey]] = $oVo;
            }else {
                $returnArray[] = $oVo;
            }
        }

        return $returnArray;
    }

    /**
     * @param $aResult
     * @param \selfMedia\Common\BaseVO $oVo
     * @return \selfMedia\Common\BaseVO
     */
    protected function resultBindForInfo($aResult, ApiBaseVo $oVo)
    {
        $resultSetPrototype = new ResultSet();
        $array = $resultSetPrototype->initialize($aResult)->toArray();
        if (empty($array)) {
            return null;
        }
        $array = current($array);
        $oVo->exchangeArray($array);
        return $oVo;
    }

    /**
     * @param $aDbResultArray array(array('id'=>1), array('id'=>2))
     * @return array
     */
    protected function parseIdsFromDbResultArray($aDbResultArray)
    {
        $aIds = array();
        foreach ($aDbResultArray as $aRow) {
            $aIds[] = current($aRow);
        }

        return $aIds;
    }

    public function delete($table, $where)
    {
        $sql = new \Zend\Db\Sql\Sql($this->dbAdapter);
        $delete = $sql->delete();
        $delete->from($table);
        $delete->where($where);

        $selectString = $sql->getSqlStringForSqlObject($delete);

        $results = $this->dbAdapter->query($selectString, dbAdapter::QUERY_MODE_EXECUTE);

        return $results->getAffectedRows();
    }
}