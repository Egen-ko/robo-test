<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 01.02.2021
 * Time: 10:24
 */

namespace App\Model;


use App\Entity\Client;

trait CheckPaymentTrait
{
    abstract public function getFromClient(): Client;
    abstract public function getToClient(): Client;
    abstract public function getAmount(): float;

    /**
     * @throws PaymentException
     */
    public function check(): void
    {
        if ($this->isSelfPayment()) {
            throw new PaymentException(sprintf(
                'Payment to self denied (Client - %s)',
                $this->getFromClient()->getName()
            ));
        }
        if ($this->isZeroOrNegativePayment()) {
            throw new PaymentException(sprintf(
                'Zero or negative payment denied (Payment - %01.2f)',
                $this->getAmount()
            ));
        }
        if (!$this->checkEnoughMoneyForPayment()) {
            throw new PaymentException(sprintf(
                'Not enough money for payment (Client - %s, Balance - %01.2f, Payment - %01.2f)',
                $this->getFromClient()->getName(), $this->getFromClient()->getBalance(), $this->getAmount()
            ));
        }
    }

    private function isSelfPayment(): bool
    {
        return $this->getFromClient() === $this->getToClient();
    }

    private function isZeroOrNegativePayment(): bool
    {
        return $this->getAmount() <= 0;
    }

    private function checkEnoughMoneyForPayment(): bool
    {
        return ($this->getFromClient()->getBalance() - $this->getAmount()) > 0;
    }
}