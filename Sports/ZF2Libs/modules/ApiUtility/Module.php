<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApiUtility;

use Sports\Config\ConfigSingle;
use \Zend\Mvc\MvcEvent;
use Sports\Log\LogWBY;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $oEventManager = $e->getApplication()->getEventManager();
        $oServiceManager = $e->getApplication()->getServiceManager();

        if($oServiceManager->has('ExceptionListener'))
            $oEventManager->attach($oServiceManager->get('ExceptionListener'));

        if($oServiceManager->has('RouteLogListener'))
            $oEventManager->attach($oServiceManager->get('RouteLogListener'));

        $aApplicationConfig = $e->getApplication()->getServiceManager()->get('config');
        $aLogConfig = $aApplicationConfig['log'];
        LogWBY::init($aLogConfig['lib'], $aLogConfig[$aLogConfig['lib']]);
    }

    /**
     * 注册Controller的Plugin
     * @return array
     */
    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'jsonResponse' => function ($sm) {
                    return new \Sports\ZF2Libs\Controller\Plugin\JsonResponse();
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ExceptionListener' => function ($sm){
                    return new \ApiUtility\Listener\ExceptionListener();
                },

                'RouteLogListener' => function ($sm){
                    return new \ApiUtility\Listener\RouteLogListener();
                },

                'Transaction' => function ($sm) {
                    return \ApiUtility\Helper\Transaction::getInstance($sm);
                },
            ),
            'services' => array(

            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
