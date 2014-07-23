<?php
namespace Sports\ApiClient\Search;

use Sports\ApiClient\Base;

class Billing extends Base
{
    public function queryList($aParams)
    {
        return $this->questApi('/search/billing/queryList', $aParams, self::METHOD_GET);
    }
}