<?php
namespace Sports\Exception;
use \Sports\Constant\ErrorCode;

class RouteFailedException extends \Exception
{
    public function __construct($sMsg)
    {
        parent::__construct($sMsg, ErrorCode::STATUS_ROUTE_ERROR);
    }
}
