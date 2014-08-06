<?php
namespace Sports\Constant;

class Finance
{
    /**
     * 表名
     */
    const TABLE_ACCOUNT = 'gt_account';
    const TABLE_BILLING = 'gt_account_billing';
    const TABLE_RELATION = 'gt_relation_account_user';
    const TABLE_OPERATE = 'gt_finance_operate';
    const TABLE_ACTION = 'gt_finance_operate_action';

    /**
     * 使用原因
     */
    const PURPOSE_ACCOUNT = 1;//默认账户
    const PURPOSE_POINTS = 2;//积分

    /**
     * 支持的操作枚举
     */
    const OPERATE_RECHARGE = 1; //充值
    const OPERATE_CONSUME = 2;  //消费
    const OPERATE_FREEZE = 3;   //冻结
    const OPERATE_UNFREEZE = 4;   //解冻

    //关联类型
    const RELATION_REVERSAL = -1; //撤销
    const RELATION_BOOKING = 1; //预定
    const RELATION_MEMBERFEE = 2; //缴纳会籍费
    const RELATION_SUBOUT = 3; //分账支出方
    const RELATION_SUBIN = 4; //分账支入方
    const RELATION_RECHARGE = 5;//充值
    const RELATION_CANCEL_BOOKING = 6;//取消预定
    const RELATION_PARTNER_COACH = 7;//陪练教练收入
    const RELATION_PARTNER_STUDENT = 8;//陪练学员支出
    const RELATION_TRAIN_STUDENT = 9;//培训费学员支出
    const RELATION_CUSTOM_IN = 10;//自定义费用得钱方
    const RELATION_CUSTOM_OUT = 11;//自定义费用出钱方
    const RELATION_BUY_INSTANT_ORDER = 12;//购买即时订单
    const RELATION_CANCEL_INSTANT_ORDER = 13;//取消购买即时订单
    const RELATION_TERMINATE_INSTANT_ORDER = 14;//执行中止即时订单
    const RELATION_SELL_INSTANT_ORDER = 15;//售出即时订单

    //账户类型
    const ACCOUNT_BALANCE = 1;  //余额
    const ACCOUNT_FREEZE = 2;   //冻结金额

    //
    static public $relationTypeOptions = array(
        self::RELATION_BOOKING => "预订场地：",
        self::RELATION_MEMBERFEE => "充值",
        self::RELATION_SUBOUT => "参与分账：",
        self::RELATION_SUBIN => "发起分账：",
        self::RELATION_RECHARGE => '',
        self::RELATION_CANCEL_BOOKING => "取消场地：",
        self::RELATION_PARTNER_COACH => "提供陪练：",
        self::RELATION_PARTNER_STUDENT => "参加陪练：",
        self::RELATION_TRAIN_STUDENT => "参加培训：",
        self::RELATION_CUSTOM_IN => "费用增加：",
        self::RELATION_CUSTOM_OUT => "费用扣除：",
        self::RELATION_BUY_INSTANT_ORDER => '购买场地：',
        self::RELATION_CANCEL_INSTANT_ORDER => '取消场地：',
        self::RELATION_TERMINATE_INSTANT_ORDER => '中止打球：',
        self::RELATION_SELL_INSTANT_ORDER => '售出场地：',

    );
}
