<?php
namespace Sports\Constant;

class Sms
{
    /**
     * 表名
     */
    const TABLE_QUEUE = 'gt_sms_queue';
    const TABLE_CHANNEL = 'sms_channel';

    const QUEUE_STATUS_SUSPENDED = 1;
    const QUEUE_STATUS_PENDING = 2;
    const QUEUE_STATUS_SENDING = 3;
    const QUEUE_STATUS_COMPLETED = 4;

    const CHANNEL_EMAY = 8887;
    const CHANNEL_BAIWU = 8888;//百悟的通道
}
