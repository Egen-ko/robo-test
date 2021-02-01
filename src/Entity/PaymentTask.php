<?php

namespace App\Entity;

use App\Model\CheckPaymentTrait;
use App\Repository\PaymentTaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Model\PaymentTaskConstraint;

/**
 * @PaymentTaskConstraint
 * @ORM\Entity(repositoryClass=PaymentTaskRepository::class)
 */
class PaymentTask
{
    use CheckPaymentTrait;

    public const STATE_NEW = 0;
    public const STATE_SUCCESS = 1;
    public const STATE_ERROR = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Positive
     * @Assert\Type(type="numeric")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     */
    private $scheduled_for;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\PositiveOrZero
     */
    private $state = self::STATE_NEW;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="payment_tasks")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $from_client;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $to_client;

    /**
     * @ORM\OneToOne(targetEntity=Payment::class, inversedBy="payment_task", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $payment;

    public function isActive(): bool
    {
        return ($this->getState() === self::STATE_NEW) && ($this->payment === null);
    }

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getScheduledfor(): ?\DateTimeInterface
    {
        return $this->scheduled_for;
    }

    public function setScheduledfor(\DateTimeInterface $scheduled_for): self
    {
        $this->scheduled_for = $scheduled_for;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

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

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
