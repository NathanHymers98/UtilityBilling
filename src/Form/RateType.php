<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
            ->add('peakRate', NumberType::class, [
                'label' => 'Peak Unit Rate (GBP pence per kWh)',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Type(['type' => 'numeric']),
                    new GreaterThan([
                        'value' => 1,
                        'message' => 'The value must be greater than 1.000.',
                    ]),
                ],
                'scale' => 3,
            ])
            ->add('offPeakRate', NumberType::class, [
                'label' => 'Off-Peak Unit Rate (GBP pence per kWh)',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Type(['type' => 'numeric']),
                    new GreaterThan([
                        'value' => 1,
                        'message' => 'The value must be greater than 1.000.',
                    ]),
                ],
                'scale' => 3,
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
