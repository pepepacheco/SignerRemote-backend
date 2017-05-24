<?php

namespace CoreBundle\Form;

use Doctrine\DBAL\Types\TimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employee', EntityType::class, array(
                'label' => 'Empleado',
                'class' => 'CoreBundle\Entity\Employee',
                'placeholder' => 'Seleccione un empleado',
                'required' => true,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('n');
                }
            ))
            ->add('sendDate', 'time', array(
                'label' => 'Seleccione una hora',
                'widget' => "single_text",
                'required' => true,
                'placeholder' => array(
                    'hour' => 'Hora', 'minute' => 'Minuto',
                )
            ))
            ->add('longitude', 'hidden')
            ->add('latitude', 'hidden')
            ->add('address', 'hidden')
            ->add('radius', 'hidden')
            ->add('dayRepeat', null, array(
                'label' => 'Repetir',
                'required' => true
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Notification'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'corebundle_notification';
    }


}
