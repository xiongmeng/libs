<?php
namespace ApiClient;

use Sports\ApiClient\Finance\Points;
use Sports\Config\ConfigSingle;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Sports\ApiClient\Finance\Points' => function ($sm){
                    $aCfg = json_decode(ConfigSingle::get('api_finance_ticket'), true);
                    $aCfg['host'] = ConfigSingle::get('api_sports_host');
                    return new Points($aCfg);
                },
            ),
        );
    }
}
