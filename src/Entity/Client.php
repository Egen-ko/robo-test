<?php

namespace App\Entity;

use App\Model\PaymentException;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=ClientBalance::class, mappedBy="client")
     * @var ClientBalance
     */
    private $balance;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="from_client", orphanRemoval=true)
     */
    private $payments;

    /**
     * @ORM\OneToMany(targetEntity=PaymentTask::class, mappedBy="from_client", orphanRemoval=true)
     */
    private $payment_tasks;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->payment_tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBalance(): float
    {
        return $this->balance->getBalance();
    }

    public function increaseBalance(float $amount): self
    {
        if (!isset($this->balance)) {
            $this->balance = new ClientBalance();
            $this->balance->setClient($this);
        }

        $this->balance->setBalance($this->balance->getBalance() + $amount);

        return $this;
    }

    public function decreaseBalance(float $amount): self
    {
        if (!isset($this->balance)) {
            $this->balance = new ClientBalance();
            $this->balance->setClient($this);
        }

        $this->balance->setBalance($this->balance->getBalance() - $amount);

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /**
     * @param Payment $payment
     * @return Client
     * @throws PaymentException
     */
    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $payment->setFromClient($this);
            $payment->check();
            $this->payments[] = $payment;
            /*$this->decreaseBalance($payment->getAmount());
            $payment->getToClient()->increaseBalance($payment->getAmount());*/
        }

        return $this;
    }

    /**
     * @return Collection|PaymentTask[]
     */
    public function getPaymentTasks(): Collection
    {
        return $this->payment_tasks;
    }

    /**
     * @param PaymentTask $payment_task
     * @return Client
     * @throws PaymentException
     */
    public function addPaymentTask(PaymentTask $payment_task): self
    {
        if (!$this->payments->contains($payment_task)) {
            $payment_task->setFromClient($this);
            $payment_task->check();
            $this->payment_tasks[] = $payment_task;
        }

        return $this;
    }
}
