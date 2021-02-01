<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 14:46
 */


use App\Entity\Client;
use App\Entity\PaymentTask;
use App\Model\PaymentTaskTrait;
use PHPUnit\Framework\TestCase;


class PaymentTaskTest extends TestCase
{
    use PaymentTaskTrait;

    /** @var  Client */
    private $client1;
    /** @var  Client */
    private $client2;

    protected function setUp(): void
    {
        $this->client1 = (new Client())
            ->setName('Сергей')
            ->increaseBalance(1000);
        $this->client2 = (new Client())
            ->setName('Светлана')
            ->increaseBalance(1300);
    }

    public function testIsActive_StateNewPaymentNull()
    {
        $task = (new PaymentTask())
            ->setFromClient($this->client1)
            ->setToClient($this->client2)
            ->setAmount(500);

        $this->assertEquals(true, $task->isActive());
    }

    public function testIsActive_StateErrorPaymentNull()
    {
        $task = (new PaymentTask())
            ->setFromClient($this->client1)
            ->setToClient($this->client2)
            ->setAmount(500)
            ->setState(PaymentTask::STATE_ERROR);

        $this->assertEquals(false, $task->isActive());
    }

    public function testIsActive_StateNewPayment()
    {
        $task = (new PaymentTask())
            ->setFromClient($this->client1)
            ->setToClient($this->client2)
            ->setAmount(500);
        $payment = $this->createPaymentFromTask($task);
        $task->setPayment($payment);

        $this->assertEquals(false, $task->isActive());
    }

    public function testIsActive_StateSucccessPayment()
    {
        $task = (new PaymentTask())
            ->setFromClient($this->client1)
            ->setToClient($this->client2)
            ->setAmount(500)
            ->setState(PaymentTask::STATE_SUCCESS);
        $payment = $this->createPaymentFromTask($task);
        $task->setPayment($payment);

        $this->assertEquals(false, $task->isActive());
    }
}
