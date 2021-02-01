<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 01.02.2021
 * Time: 10:14
 */

namespace App\Model;


use App\Entity\Payment;
use App\Entity\PaymentTask;

trait PaymentTaskTrait
{
    public function createPaymentFromTask(PaymentTask $task): Payment
    {
        return (new Payment())
            ->setFromClient($task->getFromClient())
            ->setToClient($task->getToClient())
            ->setAmount($task->getAmount())
            ->setUpdatedAt(new \DateTime());
    }
}