<?php

namespace CoreBundle\Form;

use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')

            ->add('name', 'text', array(
                'required' => true,
                'label' => 'Nombre'
            ))
            ->add('lastName', 'text', array(
                'required' => true,
                'label' => 'Apellidos'
            ))
            ->add('email', 'text', array(
                'required' => true,
                'label' => 'email'
            ))
            ->add('password', PasswordType::class, array(
                'required' => true,
                'label' => 'contraseña por defecto'
            ))
            ->add('phone', 'number', array(
                'required' => true,

                'label' => 'Teléfono'
            ))
            ->add('birthdate', 'date', array(
                'label' => 'Fecha de nacimiento',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'class' => 'datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-MM-yyyy'
                ]
            ))
            ->add('workstation', 'text', array(
                'required' => true,
                'label' => 'Puesto de trabajo'
            ))
            ->add('woman', 'choice', array(
                'label' => false,
                'choices' => array(
                    '1' => 'Sra.',
                    '0' => 'Sr.'
                ),
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'data'     => 'true'
            ));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Employee'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'corebundle_employee';
    }


}
