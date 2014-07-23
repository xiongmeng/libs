<?php
namespace Sports\ApiClient\Booking;

use Sports\ApiClient\Base;

class Points extends Base
{
    public function refreshBooking($iOrderId)
    {
        return $this->questApi('/booking/points/refreshBooking',
            array('order_id' => $iOrderId), self::METHOD_POST);
    }

    public function refreshCancelBooking($iOrderId)
    {
        return $this->questApi('/booking/points/refreshCancelBooking',
            array('order_id' => $iOrderId), self::METHOD_POST);
    }
}