<?php

/**
 * Created by PhpStorm.
 * User: Egen
 * Date: 31.01.2021
 * Time: 18:10
 */

namespace App\Form;

use App\Entity\Client;
use App\Entity\PaymentTask;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $max_amount = $options['max_amount'];

        $builder
            ->add('toClient', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name',
                'label' => 'Кому',
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Сумма',
                'html5' => true,
                'attr' => ['min' => 1, 'max' => $max_amount],
            ])
            ->add('scheduledFor', DateTimeType::class, [
                'label' => 'Дата/время перевода',
                'with_minutes' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PaymentTask::class,
            'max_amount' => 0,
        ));
    }

}