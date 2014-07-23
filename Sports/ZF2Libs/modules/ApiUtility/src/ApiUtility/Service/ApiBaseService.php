<?php
/**
 * 基础service
 * 包含loadModel方法
 */

namespace ApiUtility\Service;

use \Zend\ServiceManager\ServiceManager;

class ApiBaseService
{
    /**
     * @var ServiceManager
     */
    protected $oServiceManager;
    /**
     * @var \ApiUtility\Helper\Transaction;
     */
    public $transaction = NULL;

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function __construct($serviceManager)
    {
        $this->oServiceManager = $serviceManager;
        $this->transaction = $this->oServiceManager->get("Transaction");
        return $this->oServiceManager;
    }

    /**
     * 获取服务管家
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->oServiceManager;
    }

    /**
     * 获取主库Adapter
     * @return array|object
     */
    public function getMasterDbAdapter()
    {
        return $this->oServiceManager->get('Zend\Db\Adapter\Adapter');
    }

    /**
     * 获取从库Adapter
     */
    public function getSalveDbAdapter()
    {
        return $this->oServiceManager->get('Zend\Db\Adapter\Adapter');
    }

    /**
     * @param $modelName
     * @return $modelName
     */
    protected function loadModel($modelName)
    {
        return new $modelName($this->getServiceManager());
    }
}