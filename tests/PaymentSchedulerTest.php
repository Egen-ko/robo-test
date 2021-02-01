<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 15:40
 */

use App\Entity\Client;
use App\Entity\Payment;
use App\Entity\PaymentTask;
use App\Model\PaymentScheduler;
use PHPUnit\Framework\TestCase;


class PaymentSchedulerTest extends TestCase
{
    /** @var  PaymentScheduler */
    private $scheduler;
    /** @var  Client */
    private $client1;
    /** @var  Client */
    private $client2;
    /** @var  PaymentTask */
    private $active_task;
    /** @var  PaymentTask */
    private $error_task;
    /** @var  PaymentTask */
    private $self_pay_task;
    /** @var  PaymentTask */
    private $zero_task;
    /** @var  PaymentTask */
    private $big_task;

    protected function setUp(): void
    {
        $this->scheduler = new PaymentScheduler();
        $this->client1 = (new Client())
            ->setName('Владимир')
            ->increaseBalance(1000);
        $this->client2 = (new Client())
            ->setName('Михаил')
            ->increaseBalance(1000);

        $this->active_task = (new PaymentTask())
            ->setFromClient($this->client1)
            ->setToClient($this->client2)
            ->setAmount(200)
            ->setCreatedAt(new \DateTime())
            ->setScheduledfor((new DateTime())->add(\DateInterval::createFromDateString('+3 hour')));
        $this->error_task = (clone $this->active_task)
            ->setState(PaymentTask::STATE_ERROR);
        $this->self_pay_task = (clone $this->active_task)
            ->setToClient($this->client1);
        $this->zero_task = (clone $this->active_task)
            ->setAmount(0);
        $this->big_task = (clone $this->active_task)
            ->setAmount(12000);
    }

    public function testSchedule()
    {
        $tasks = [$this->active_task, $this->error_task, $this->self_pay_task, $this->zero_task, $this->big_task, null];
        $payments = $this->scheduler->schedule($tasks);

        $this->assertEquals(1, count($payments), 'wrong count');
        $this->assertNotNull($this->active_task->getPayment(), 'wrong payment');
        $this->assertEquals(800, $this->client1->getBalance(), 'wrong client1.balance');
        $this->assertEquals(1200, $this->client2->getBalance(), 'wrong client2.balance');
        $this->assertEquals(PaymentTask::STATE_SUCCESS, $this->active_task->getState(), 'wrong active_task.state ');
        $this->assertEquals(PaymentTask::STATE_ERROR, $this->error_task->getState(), 'wrong error_task.state');
        $this->assertEquals(PaymentTask::STATE_ERROR, $this->self_pay_task->getState(), 'wrong self_pay_task.state');
        $this->assertEquals(PaymentTask::STATE_ERROR, $this->zero_task->getState(), 'wrong zero_task.state');
        $this->assertEquals(PaymentTask::STATE_ERROR, $this->big_task->getState(), 'wrong big_task.state');
    }
}
