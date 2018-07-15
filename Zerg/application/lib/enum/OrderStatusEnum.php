<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/22
 * Time: 17:12
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //待支付
    const UNPAID = 1;

    //已支付
    const PAID = 2;

    //已发货
    const DELIVERED = 3;

    //已支付、但是库存量不足
    const PAID_BUI_OUT_OF = 4;
}