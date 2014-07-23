<?php

namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\UniqueVO;

use \Zend\Db\Adapter\Adapter as DbAdapter;
use \Zend\Db\ResultSet\ResultSet as DbResult;

class StorageDatabase extends StorageAbstract
{
    /**
     * @var DbAdapter
     */
    private $oDbAdapter = null;

    /**
     * @return null|\Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return $this->oDbAdapter;
    }

    /**
     * 数据库的连接配置信息
     * @var array
     */
    private $aConfig = array();

    public function getTableName()
    {
        return isset($this->aConfig['table']) ? $this->aConfig['table'] : 'config';
    }

    public function getHostName()
    {
        return isset($this->aConfig['hostname']) ? $this->aConfig['hostname'] : '';
    }

    public function getPort()
    {
        return isset($this->aConfig['port']) ? $this->aConfig['port'] : '';
    }

    public function getDatabase()
    {
        return isset($this->aConfig['database']) ? $this->aConfig['database'] : '';
    }

    /**
     * @param Array $aConfig
     *  Key            Is Required?                Value
     *  driver        required                    Mysqli, Sqlsrv, Pdo_Sqlite, Pdo_Mysql, Pdo=OtherPdoDriver
     *  table       required                    the name of the config table
     *  database    generally required            the name of the database (schema)
     *  username    generally required            the connection username
     *  password    generally required            the connection password
     *  hostname    not generally required        the IP address or hostname to connect to
     *  port        not generally required        the port to connect to (if applicable)
     *  charset        not generally required        the character set to use
     */
    public function __construct($aConfig)
    {
        $this->aConfig = $aConfig;
        $this->oDbAdapter = new DbAdapter($aConfig);
    }

    /**
     * @param ConfigVO[] $mConfigVO
     * @return mixed|void
     */
    protected function putConfigArray(&$aConfigVOArray)
    {
        foreach ($aConfigVOArray as $oConfigVO) {
            $this->putSingleConfig($oConfigVO);
        }
    }

    /**
     * @param ConfigVO $oConfigVO
     */
    private function putSingleConfig(&$oConfigVO)
    {
        if ($oConfigVO->getApp() === null || $oConfigVO->getKey() === null || $oConfigVO->getExt() === null) {
            throw new \Exception('please ensure app,key,ext are set in $oConfigVO');
        }

        if ($this->checkConfigIsExisted($oConfigVO)) {
            $this->update($oConfigVO);
        } else {
            $this->insert($oConfigVO);
        }
    }

    /**
     * 查询指定的ConfigVO是否存在
     * @param ConfigVO $oConfigVO
     */
    public function checkConfigIsExisted(&$oConfigVO)
    {
        $sSql = sprintf("SELECT * FROM %s WHERE `app`='%s' AND `key`='%s' AND `ext`='%s'",
            $this->getTableName(), $oConfigVO->getApp(), $oConfigVO->getKey(), $oConfigVO->getExt());

        $aResult = $this->oDbAdapter->query($sSql, DbAdapter::QUERY_MODE_EXECUTE);
        return !empty($aResult) && $aResult->count() > 0;
    }

    /**
     * 插入一条记录
     * @param ConfigVO $oConfigVO
     */
    private function insert(&$oConfigVO)
    {
        $sSql = sprintf("INSERT INTO %s(`app`,`key`,`ext`,`value`,`enabled`,`description`)
            VALUES ('%s','%s','%s','%s','%s','%s')",
            $this->getTableName(), $oConfigVO->getApp(), $oConfigVO->getKey(), $oConfigVO->getExt(),
            $oConfigVO->getValue(), $oConfigVO->getEnabled(), $oConfigVO->getDescription());

        return $this->oDbAdapter->query($sSql, DbAdapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 更新一条记录
     * @param ConfigVO $oConfigVO
     */
    private function update(&$oConfigVO)
    {
        $aSetCondition = array();
        if ($oConfigVO->getValue() !== null) {
            $aSetCondition[] = sprintf("`value`='%s'", $oConfigVO->getValue());
        }
        if ($oConfigVO->getEnabled() !== null) {
            $aSetCondition[] = sprintf("`enabled`='%s'", $oConfigVO->getEnabled());
        }
        if ($oConfigVO->getDescription() !== null) {
            $aSetCondition[] = sprintf("`description`='%s'", $oConfigVO->getDescription());
        }
        if (!empty($aSetCondition)) {
            $sSql = sprintf("UPDATE %s SET %s WHERE `app`='%s' AND `key`='%s' AND `ext`='%s'", $this->getTableName(),
                join(',', $aSetCondition), $oConfigVO->getApp(), $oConfigVO->getKey(), $oConfigVO->getExt());

            return $this->oDbAdapter->query($sSql, DbAdapter::QUERY_MODE_EXECUTE);
        }
        return false;
    }

    /**
     * @param UniqueVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function query($oQueryVO = null)
    {
        $sSql = sprintf('SELECT * FROM %s ', $this->getTableName());
        $sWhere = 'WHERE 1 ';
        $sWhere .= sprintf(" AND `enabled`='%d'", ConfigVO::ENABLED_TRUE);

        if ($oQueryVO instanceof \Sports\Config\VO\UniqueVO) {
            if ($oQueryVO->getApp() !== null) {
                $sWhere .= sprintf(" AND `app`='%s'", $oQueryVO->getApp());
            }
            if ($oQueryVO->getKey() !== null) {
                $sWhere .= sprintf(" AND `key`='%s'", $oQueryVO->getKey());
            }
            if ($oQueryVO->getExt() !== null) {
                $sWhere .= sprintf(" AND `ext`='%s'", $oQueryVO->getExt());
            }
        }
        $sSql .= $sWhere;

        $aResult = $this->oDbAdapter->query($sSql, DbAdapter::QUERY_MODE_EXECUTE);

        return $this->constructByResultSet($aResult);
    }

    /**
     * 将来供后台管理查询用
     * @param UniqueVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function queryFuzzy($oQueryVO = null)
    {
        /**
         * TODO 更多元素的获取
         */
        $sSql = sprintf('SELECT * FROM %s ', $this->getTableName());
        $sWhere = 'WHERE 1 ';
        $sWhere .= sprintf(" AND `enabled`='%d'", ConfigVO::ENABLED_TRUE);

        if ($oQueryVO instanceof \Sports\Config\VO\UniqueVO) {
            if ($oQueryVO->getApp() !== null) {
                $sWhere .= " AND `app` LIKE '%" . $oQueryVO->getApp() . "%'";
            }
            if ($oQueryVO->getKey() !== null) {
                $sWhere .= " AND `key` LIKE '%" . $oQueryVO->getKey() . "%'";
            }
            if ($oQueryVO->getExt() !== null) {
                $sWhere .= " AND `ext` LIKE '%" . $oQueryVO->getExt() . "%'";
            }
        }
        $sSql .= $sWhere;

        $aResult = $this->oDbAdapter->query($sSql, DbAdapter::QUERY_MODE_EXECUTE);

        return $this->constructByResultSet($aResult);
    }

    /**
     * @param DbResult $oResultSet
     */
    private function constructByResultSet($oResultSet)
    {
        if (!empty($oResultSet) && $oResultSet->count() > 0) {
            $aCfgArray = $oResultSet->toArray();
            $aCfgVOArray = array();
            foreach ($aCfgArray as $aCfg) {
                $aCfgVOArray[] = ConfigVO::constructByArray($aCfg);
            }
            return $aCfgVOArray;
        }
        return null;
    }

    /**
     * @param StorageAbstract $oStorage
     * @return boolean
     */
    public function equal($oStorage)
    {
        if ($oStorage instanceof StorageDatabase) {
            return ($this->getHostName() == $oStorage->getHostName())
                && ($this->getPort() == $oStorage->getPort()
                    && ($this->getDatabase() == $oStorage->getDatabase())
                    && ($this->getTableName() == $oStorage->getTableName()));
        }
        return false;
    }
}
