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
                    'Car Purchase Appointment (1 hour)' => Appointment::TYPE_CAR_PURCHASE,
                    'Maintenance (2 hours)' => Appointment::TYPE_MAINTENANCE,
                    'Repair (3 hours)' => Appointment::TYPE_REPAIR,
                    'BMW M Upgrade (4 hours)' => Appointment::TYPE_BMW_M_UPGRADE,
                ],
                'placeholder' => 'Select an appointment type',
                'label' => 'Appointment Type',
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Start Time',
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
