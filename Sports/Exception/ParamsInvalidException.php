<?php
namespace Sports\Exception;
use \Sports\Constant\ErrorCode;

class ParamsInvalidException extends \Exception
{
    public function __construct($sMsg)
    {
        parent::__construct($sMsg, ErrorCode::STATUS_ERROR_PARAMS_VALIDE);
    }
}
