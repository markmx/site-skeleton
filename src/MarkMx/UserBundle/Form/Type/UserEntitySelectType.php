<?php
namespace MarkMx\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Count;
use MarkMx\UserBundle\Validator\Constraints\ContainsNonAdminUsers;

class UserEntitySelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', 'choice',
                array(
                    'choices'   => array(
                        'empty_value'   => '---Action---',
                        'activate'      => 'Activate',
                        'deactivate'    => 'Deactivate'
                    ),
                    'required'  => true,
                )
            )
            ->add('foo_id', 'entity',
                array(
                    'required'      => false,
                    'class'         => 'MarkMxUserBundle:User',
                    'property'      => 'id',
                    'property_path' => '[id]',
                    'multiple'      => true,
                    'expanded'      => true,
                    'constraints'   => array(
                        new Count(
                            array(
                                'min' => 1,
                                'minMessage' => 'You must check at least one user account.'
                            )
                        ),
                        new ContainsNonAdminUsers(),
                    ),
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => null,
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'user_entity_collection';
    }
}
