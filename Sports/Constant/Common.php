<?php
namespace Sports\Constant;

class Common
{
    /** data cols value in db */
    const UNKNOWN = 0;
    const YES = 1;
    const NO  = 2;

    const UNKNOWN_NAME = "未知";
    const YES_NAME = "是";
    const NO_NAME = "否";

    /** 性别 */
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_UNKNOWN = 3;

    const GENDER_MALE_NAME = "男";
    const GENDER_FEMALE_NAME = "女";
    const GENDER_UNKNOWN_NAME = "未知";

    public static $genderArray = array(
        self::GENDER_MALE => self::GENDER_MALE_NAME,
        self::GENDER_FEMALE => self::GENDER_FEMALE_NAME,
        self::GENDER_UNKNOWN => self::GENDER_UNKNOWN_NAME
    );

    /** 默认选择 */
    const SELECT_DEFAULT_NAME = "请选择";
    const SELECT_DEFAULT_VALUE = "";

    const ORDER_BY_DESC = "DESC";
    const ORDER_BY_ASC = "ASC";

    const ORDER_BY_DESC_NAME = "降序";
    const ORDER_BY_ASC_NAME = "升序";

    public static $orderByArray = array(
        self::ORDER_BY_ASC => self::ORDER_BY_ASC_NAME,
        self::ORDER_BY_DESC => self::ORDER_BY_DESC_NAME,
    );

}
