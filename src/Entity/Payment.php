<?php

namespace App\Entity;

use App\Model\CheckPaymentTrait;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    use CheckPaymentTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $from_client;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $to_client;

    /**
     * @ORM\OneToOne(targetEntity=PaymentTask::class, mappedBy="payment", cascade={"persist", "remove"})
     */
    private $payment_task;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFromClient(): ?Client
    {
        return $this->from_client;
    }

    public function setFromClient(?Client $from_client): self
    {
        $this->from_client = $from_client;

        return $this;
    }

    public function getToClient(): ?Client
    {
        return $this->to_client;
    }

    public function setToClient(?Client $to_client): self
    {
        $this->to_client = $to_client;

        return $this;
    }

    public function getPaymentTask(): ?PaymentTask
    {
        return $this->payment_task;
    }

    public function setPaymentTask(?PaymentTask $payment_task): self
    {
        // unset the owning side of the relation if necessary
        if ($payment_task === null && $this->payment_task !== null) {
            $this->payment_task->setPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($payment_task !== null && $payment_task->getPayment() !== $this) {
            $payment_task->setPayment($this);
        }

        $this->payment_task = $payment_task;

        return $this;
    }
}
