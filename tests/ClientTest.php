<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 11:11
 */


use App\Entity\Client;
use App\Entity\Payment;
use App\Entity\PaymentTask;
use App\Model\PaymentException;
use PHPUnit\Framework\TestCase;


class ClientTest extends TestCase
{
    /** @var  Client */
    private $from_client;
    /** @var  Client */
    private $to_client;
    /** @var  Payment */
    private $payment;
    /** @var  PaymentTask */
    private $payment_task;

    protected function setUp(): void
    {
        $this->from_client = new Client();
        $this->from_client->setName('Василий');

        $this->to_client = new Client();
        $this->to_client->setName('Марина');

        $this->payment = new Payment();
        $this->payment->setFromClient($this->from_client);
        $this->payment->setToClient($this->to_client);
        $this->payment->setUpdatedAt(new DateTime());

        $this->payment_task = new PaymentTask();
        $this->payment_task->setFromClient($this->from_client);
        $this->payment_task->setToClient($this->to_client);
        $this->payment_task->setCreatedAt(new DateTime());
        $this->payment_task->setScheduledfor((new DateTime())->add(\DateInterval::createFromDateString('+2 hour')));
        $this->payment_task->setState(PaymentTask::STATE_NEW);
    }

    public function testAddPaymentSuccess()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment->setAmount(500);
        $this->from_client->addPayment($this->payment);

        $this->assertEquals(1200, $this->from_client->getBalance(), 'wromg balance');
        $this->assertEquals(1, count($this->from_client->getPayments()), 'wrong count(payments)');
        $this->assertEquals($this->payment, $this->from_client->getPayments()[0], 'wrong payment');
    }

    public function testAddPaymentException_NotEnoughMoney()
    {
        $this->from_client->increaseBalance(400);
        $this->payment->setAmount(500);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Not enough money for payment (Client - Василий, Balance - 400.00, Payment - 500.00)');

        $this->from_client->addPayment($this->payment);
    }

    public function testAddPaymentException_SelfPayment()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment->setAmount(500);
        $this->payment->setToClient($this->from_client);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Payment to self denied (Client - Василий)');

        $this->from_client->addPayment($this->payment);
    }

    public function testAddPaymentException_ZeroPayment()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment->setAmount(0);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Zero or negative payment denied (Payment - 0.00)');

        $this->from_client->addPayment($this->payment);
    }

    public function testAddPaymentException_NegativePayment()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment->setAmount(-200);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Zero or negative payment denied (Payment - -200.00)');

        $this->from_client->addPayment($this->payment);
    }

    public function testAddPaymentTaskSuccess()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment_task->setAmount(500);
        $this->from_client->addPaymentTask($this->payment_task);

        $this->assertEquals(1200, $this->from_client->getBalance(), 'wromg balance');
        $this->assertEquals(1, count($this->from_client->getPaymentTasks()), 'wrong count(payment_tasks)');
        $this->assertEquals($this->payment_task, $this->from_client->getPaymentTasks()[0], 'wrong payment_task');
    }

    public function testAddPaymentTaskException_NotEnoughMoney()
    {
        $this->from_client->increaseBalance(400);
        $this->payment_task->setAmount(500);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Not enough money for payment (Client - Василий, Balance - 400.00, Payment - 500.00)');

        $this->from_client->addPaymentTask($this->payment_task);
    }

    public function testAddPaymentTaskException_SelfPayment()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment_task->setAmount(500);
        $this->payment_task->setToClient($this->from_client);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Payment to self denied (Client - Василий)');

        $this->from_client->addPaymentTask($this->payment_task);
    }

    public function testAddPaymentTaskException_ZeroPayment()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment_task->setAmount(0);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Zero or negative payment denied (Payment - 0.00)');

        $this->from_client->addPaymentTask($this->payment_task);
    }

    public function testAddPaymentTaskException_NegativePayment()
    {
        $this->from_client->increaseBalance(1200);
        $this->payment_task->setAmount(-200);
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Zero or negative payment denied (Payment - -200.00)');

        $this->from_client->addPaymentTask($this->payment_task);
    }
}
