<?php

namespace MarkMx\SkelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetDbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('confirm', 'checkbox', array(
            'label'         => 'Confirm current DB data will be destroyed and replaced with fixtures data.',
            'required'      => true,
            'constraints'   => array(
                new NotBlank(array(
                    'message' => 'Confirm box must be checked to reset the DB.'
                ))
            ),
        ));
    }

    public function getName()
    {
        return 'reset_db';
    }
}
