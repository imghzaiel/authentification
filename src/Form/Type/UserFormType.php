<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

/**
 * Description of Client
 *
 * @author imen
 */
class UserFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username', TextType::class)
                ->add('password', TextType::class)
                ->add('isActive', TextType::class);
               
                
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
    
}
