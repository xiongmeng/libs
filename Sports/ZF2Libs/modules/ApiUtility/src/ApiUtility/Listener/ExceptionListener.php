<?php
namespace ApiUtility\Listener ;

use Sports\ZF2Libs\Response\Json\ExceptionModel;
use \Zend\EventManager\ListenerAggregateInterface;
use \Zend\EventManager\EventManagerInterface;

use Sports\Log\LogWBY;

use \Zend\Mvc\MvcEvent;
use \Zend\Mvc\Application;
use \Sports\Exception\RouteFailedException;
use Zend\Http\Request;

class ExceptionListener implements ListenerAggregateInterface
{
    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this,'onDispatchError'), 100);
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        $events->detach(array($this, 'onDispatchError'));
    }

    /**
     * @param \Zend\Mvc\MvcEvent $oEvent
     */
    public function onDispatchError(MvcEvent $oEvent)
    {
        $oException = NULL;
        switch ($oEvent->getError()) {
            case Application::ERROR_CONTROLLER_CANNOT_DISPATCH :
            case Application::ERROR_CONTROLLER_INVALID :
            case Application::ERROR_CONTROLLER_NOT_FOUND :
            case Application::ERROR_ROUTER_NO_MATCH :
                $oException = new RouteFailedException($oEvent->getError());
                break;
            case Application::ERROR_EXCEPTION:
                $oException = $oEvent->getParam('exception');
                break;
        }
        $oEvent->stopPropagation(true);

        $oEvent->setViewModel($this->prepareExceptionModel($oException));

        $this->recordLog($oEvent, $oException);
    }

    /**
     * @param MvcEvent $oEvent
     */
    private function recordLog(MvcEvent $oEvent, \Exception $oException)
    {
        if(!class_exists('Sports\Log\LogWBY')){
            return ;
        }

        $oRequest = $oEvent->getRequest();
        if(!$oRequest instanceof Request){
            return ;
        }

        $aData['uri'] = $oRequest->getUri()->toString();

        $aData['exception'] = array(
            'code' => $oException->getCode(),
            'msg' => $oException->getMessage(),
            'data' => $oException->getTraceAsString()
        );

        LogWBY::info('exception : ' . json_encode($aData), 'exception');
    }

    /**
     * @param $oException
     * @return ExceptionModel
     */
    protected function prepareExceptionModel($oException)
    {
        return new ExceptionModel($oException);
    }
}
