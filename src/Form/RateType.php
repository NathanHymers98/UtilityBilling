<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class RateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     * @return void
     */
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

    /**
     * @param OptionsResolver $resolver
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
