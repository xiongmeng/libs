<?php
namespace Sports\ZF2Libs\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use \Sports\Exception\RouteFailedException;

use Zend\Http\Response as HttpResponse;

/**
 * @method \Sports\ZF2Libs\Controller\Plugin\JsonResponse jsonResponse()
 */
abstract class BaseController extends AbstractActionController
{
    public function __construct()
    {

    }

    /**
     * @return array|void
     * @throws \Sports\Exception\RouteFailedException
     */
    public function notFoundAction()
    {
        throw new RouteFailedException('action is not existed!');
    }
}