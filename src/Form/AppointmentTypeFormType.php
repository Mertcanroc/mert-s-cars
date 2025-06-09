<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('appointmentType', ChoiceType::class, [
                'choices' => [
                    'Auto aanschaf afspraak' => Appointment::TYPE_AUTO_AANSCHAF,
                    'Onderhoud' => Appointment::TYPE_ONDERHOUD,
                    'Reparatie' => Appointment::TYPE_REPARATIE,
                    'BMW M upgrade' => Appointment::TYPE_BMW_M_UPGRADE,
                ],
                'placeholder' => 'Kies een type afspraak',
                'label' => 'Type afspraak',
            ])
            ->add('startTime', DateType::class, [
                'label' => 'Datum',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'Begintijd',
                'input'  => 'datetime',
                'widget' => 'choice',
                'hours' => range(10, 17),
                'minutes' => [0, 15, 30, 45],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}

