<?php
namespace ApiUtility\Helper;

use Zend\Db\Adapter\Adapter as DbAdapter;
class Transaction
{
    protected $transLevel = 0;

    private $_serviceManager = NULL;

    private static $_instance = NULL;

    private $_adapter = NULL;

    private function __construct(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $this->_serviceManager = $serviceManager;
        $this->_adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
    }

    public static function getInstance(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self($serviceManager);
        }
        return self::$_instance;
    }

    /*
     * 开启事物
     */
    public function startTransaction()
    {
        if (!$this->nestable() || $this->transLevel == 0) {
            $this->_adapter->query("START TRANSACTION", DbAdapter::QUERY_MODE_EXECUTE);
            $this->resetTransLevel();
        }
        $this->transLevel++;
    }
    /*
        * 事物回滚
        */
    public function rollback()
    {
        $this->transLevel--;

        if (!$this->nestable() || $this->transLevel == 0) {
            $this->_adapter->query("ROLLBACK;", DbAdapter::QUERY_MODE_EXECUTE);
            $this->resetTransLevel();
        }
    }

    /*
     * 事物提交
     */
    public function commit()
    {
        $this->transLevel--;

        if (!$this->nestable() || $this->transLevel == 0) {
            $this->_adapter->query("COMMIT;", DbAdapter::QUERY_MODE_EXECUTE);
            $this->resetTransLevel();
        }
    }


    /*
     * 是否使用计数器
     */
    private function nestable()
    {
        return true;
    }

    /*
     * 重置计数器
     */
    private function resetTransLevel()
    {
        if (!$this->nestable()) {
            $this->transLevel = 0;
        }
    }
}