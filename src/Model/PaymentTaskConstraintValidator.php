<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 20:57
 */

namespace App\Model;

use App\Entity\PaymentTask;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class PaymentTaskConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var PaymentTask $task */
        $task = $value;
        if ($task->getFromClient() === $task->getToClient()) {
            $this->context->buildViolation('Нельзя совершить платеж самому себе.')
                ->atPath('toClient')
                ->addViolation();
        }
    }

}