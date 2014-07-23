<?php
/**
 * 基础model
 */
namespace ApiUtility\Vo;

use Sports\Interfaces\Data\ISerializable;

abstract class ApiBaseObject implements ISerializable
{
    public function __construct($data = NULL)
    {
        if (!empty($data)) {
            $this->exchangeArray($data);
        }
    }

    public function exchangeArray(array $data)
    {
       $keys = get_object_vars($this);
       foreach ($keys as $key=>$val) {
           $this->$key = isset($data[self::strFormatToLower($key)]) ? $data[self::strFormatToLower($key)] : NULL;
       }
    }

    public function getFunctionName($name)
    {
        if (empty($name) || !is_string($name)) {
            return false;
        }

        $functionName = "get" . ucfirst(self::strFormatToUpper($name));
        if (!method_exists($this, $functionName)) {
            return false;
        }
        return $functionName;
    }

    /**
     * 转换数组key
     * 转换为骆峰法
     * @param $str
     * @return string
     */
    public static function strFormatToUpper($str)
    {
        $arr = explode("_", $str);
        $new_arr = array();
        if (is_array($arr)) {
            foreach ($arr as $arr_info) {
                $new_arr[] = ucfirst($arr_info);
            }
        }
        return !empty($new_arr) ? implode('', $new_arr) : '';
    }

    /**
     * 转换数组key
     * 骆峰法转换为下划线
     * @param $str
     * @return string
     */
    public static function strFormatToLower($str)
    {
        $arr = preg_split("/(?=[A-Z])/", $str);
        $new_arr = array();
        if (is_array($arr)) {
            foreach ($arr as $arr_info) {
                $new_arr[] = strtolower($arr_info);
            }
        }
        return !empty($new_arr) ? implode('_', $new_arr) : '';
    }

    public function toArraySerializable()
    {
        $array = array();
        $arraySerializable = get_object_vars($this);
        foreach($arraySerializable as $varName => $varVal) {
            $varName = self::strFormatToLower($varName);
            $array[$varName] = ($varVal instanceof ISerializable) ?
                                $varVal->toArraySerializable() : $varVal;
        }
        return $array;
    }
}