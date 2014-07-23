<?php

namespace Sports\ZF2Libs\Response\Json;

class ExceptionModel extends DataModel
{
    public function __construct(\Exception $oException)
    {
        parent::__construct($oException->getCode(),
            $oException->getMessage(), $oException->getTraceAsString());
    }
}
