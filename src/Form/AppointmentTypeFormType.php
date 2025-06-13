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
                    'Auto aanschaf afspraak (1 uur)' => Appointment::TYPE_AUTO_AANSCHAF,
                    'Onderhoud (2 uur)' => Appointment::TYPE_ONDERHOUD,
                    'Reparatie (3 uur)' => Appointment::TYPE_REPARATIE,
                    'BMW M upgrade (4 uur)' => Appointment::TYPE_BMW_M_UPGRADE,
                ],
                'placeholder' => 'Kies een type afspraak',
                'label' => 'Type afspraak',
            ])
            ->add('date', DateType::class, [
                'label' => 'Datum',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Begintijd',
                'input' => 'datetime',
                'widget' => 'single_text',
                'with_minutes' => true,
                'with_seconds' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
