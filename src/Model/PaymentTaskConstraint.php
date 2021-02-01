<?php
/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 20:53
 */

namespace App\Model;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class PaymentTaskConstraint extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}