<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class RateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('peakRate', MoneyType::class, [
                'label' => 'Peak Unit Rate (GBP pence per kWh)',
                'currency' => 'GBP'
            ])
            ->add('offPeakRate', MoneyType::class, [
                'label' => 'Off-Peak Unit Rate (GBP pence per kWh)',
                'currency' => 'GBP'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
