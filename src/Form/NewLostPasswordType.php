<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewLostPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,
				'invalid_message' => 'Les mots de passe ne sont pas identiques',
				'first_options' => ['label' => 'Mot de passe* : ', 'attr' => ['placeholder' => '*******']],
				'second_options' => ['label' => 'Répétez le mot de passe* : ', 'attr' => ['placeholder' => '*******']],
				'required' => true));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
