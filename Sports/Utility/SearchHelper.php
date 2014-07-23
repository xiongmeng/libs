<?php
namespace Sports\Utility;

use Sports\Exception\LogicException;
use Sports\Exception\ParamsInvalidException;
use Sports\MetaData\MetaDataClient;

class SearchHelper
{
    /**
     * 拼接成以  xx-yy or -yy or xx- or -
     * @param $aParams
     * @param $sBeginKey
     * @param $sEndKey
     * @return string
     */
    static public function contactDateRange($aParams, $sBeginKey, $sEndKey)
    {
        return sprintf('%s-%s',
            isset($aParams[$sBeginKey]) ? strtotime($aParams[$sBeginKey]) : '',
            isset($aParams[$sEndKey]) ? strtotime($aParams[$sEndKey]) : ''
        );
    }

    static public function contactQuery($aParams, $aKeys)
    {
        $aKeyValueQuery = array();
        foreach($aKeys as $sKey){
            if(isset($aParams[$sKey])){
                $aKeyValueQuery[] = sprintf('@%s %s',$sKey, $aParams[$sKey]);
            }
        }

        return count($aKeyValueQuery) > 0  ? join(' ', $aKeyValueQuery) : '';
    }
}