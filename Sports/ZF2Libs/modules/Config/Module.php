<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Config;

use Zend\Mvc\MvcEvent;
use Sports\Config\ConfigSingle;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $aApplicationConfig = $e->getApplication()->getServiceManager()->get('config');
        $aConfig = $aApplicationConfig['config'];
        ConfigSingle::init($aConfig['adapter'], $aConfig['data']);
    }
}
