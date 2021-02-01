<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 13:03
 */

namespace App\Model;



use App\Entity\Payment;
use App\Entity\PaymentTask;

class PaymentScheduler
{
    use PaymentTaskTrait;

    /**
     * @param PaymentTask[] $tasks
     * @return Payment[]
     */
    public function schedule(array $tasks): array
    {
        /** @var Payment[] $payments */
        $payments = [];
        /** @var PaymentTask $task */
        foreach ($tasks as $task) {
            if (!isset($task))
                continue;
            if (!$task->isActive())
                continue;

            $payment = null;
            try {
                $payment = $this->createPaymentFromTask($task);
                $task->getFromClient()->addPayment($payment);
                $payment->getFromClient()->decreaseBalance($payment->getAmount());
                $payment->getToClient()->increaseBalance($payment->getAmount());
                $payments[] = $payment;
                $task->setState(PaymentTask::STATE_SUCCESS);
                $task->setPayment($payment);
            } catch (PaymentException $e) {
                $task->setState(PaymentTask::STATE_ERROR);
            }
        }

        return $payments;
    }
}