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
    const RELATION_CANCEL_BOOKING = 6;//取消预定
    const RELATION_RECHARGE = 5;//充值

    //账户类型
    const ACCOUNT_BALANCE = 1;  //余额
    const ACCOUNT_FREEZE = 2;   //冻结金额
}
