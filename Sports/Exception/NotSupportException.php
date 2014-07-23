<?php
namespace Sports\Exception;
use \Sports\Constant\ErrorCode;

class NotSupportException extends \Exception
{
    public function __construct($sMsg = '')
    {
        empty($sMsg) && $sMsg = 'is not support';
        parent::__construct($sMsg, ErrorCode::STATUS_ERROR_LOGIC_NOT_SUPPORT);
    }
}
