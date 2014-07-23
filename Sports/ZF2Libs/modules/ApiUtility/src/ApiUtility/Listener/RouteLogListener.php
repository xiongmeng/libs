<?php
namespace ApiUtility\Listener ;

use \Zend\EventManager\ListenerAggregateInterface;
use \Zend\EventManager\EventManagerInterface;

use \Zend\Mvc\MvcEvent;
use Zend\Http\Request;
use Zend\Http\Response;

use Sports\Log\LogWBY;

class RouteLogListener implements ListenerAggregateInterface
{
    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_ROUTE, array($this,'onDispatchRoute'), -1);
        $events->attach(MvcEvent::EVENT_RENDER, array($this,'onDispatchRender'), -1);
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        $events->detach(array($this, 'onDispatchRoute'));
        $events->detach(array($this, 'onDispatchRender'));
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onDispatchRoute(MvcEvent $oEvent)
    {
        $oRequest = $oEvent->getRequest();
        if(!$oRequest instanceof Request){
            return ;
        }

        if(!class_exists('Sports\Log\LogWBY')){
            return ;
        }

        $aData = array();
        if($oRequest->isGet()){
            $aData[$oRequest->getMethod()] = $oRequest->getQuery();
        }elseif($oRequest->isPost()){
            $aData[$oRequest->getMethod()] = $oRequest->getPost();
        }

        $aData['uri'] = $oRequest->getUri()->toString();

        LogWBY::info(sprintf('request : %s', json_encode($aData)),  'route');
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onDispatchRender(MvcEvent $oEvent)
    {
        if(!class_exists('Sports\Log\LogWBY')){
            return ;
        }

        $oRequest = $oEvent->getRequest();
        if(!$oRequest instanceof Request){
            return ;
        }

        $aData['uri'] = $oRequest->getUri()->toString();
        $aVariables = $oEvent->getViewModel()->getVariables();
        $aData['res'] = json_encode($aVariables);

        LogWBY::info('response : ' . json_encode($aData), 'route');
    }
}
