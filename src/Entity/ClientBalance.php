<?php

namespace App\Entity;

use App\Repository\ClientBalanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientBalanceRepository::class)
 */
class ClientBalance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $balance = 0;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="balance")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
