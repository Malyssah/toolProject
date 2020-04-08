<?php

namespace App\Form;

use App\Entity\Serveur;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$creation = $options['creation']; //option creation pour l'ajout et edit user
		$roles = $options['roles'];
		$builder
			->add('email', EmailType::class, array(
				'label' => 'Email: '
			))
			->add('username', TextType::class, array(
				'label' => 'Pseudo: '
			))
			->add('serveur', EntityType::class, array(
				'class' => Serveur::class,
				'placeholder' => 'Choisissez votre serveur: ',
				'multiple' => false,
				'expanded' => false,
			));

		// Gestion des rôles réservé à l'admin uniquement
			if (in_array('ROLE_ADMIN',$roles)){
				$builder
					->add('roles', ChoiceType::class, array(
						//'is_granted_attribute' => 'ROLE_ADMIN',
						'label' => 'Rôles: ',
						'placeholder' => 'Sélectionnez un rôle',
						'choices' => array(
							'Admin ' => 'ROLE_ADMIN',
							'Modérateur ' => 'ROLE_MOD',
							'User ' => 'ROLE_USER'
						),
						'expanded' => true,
						'multiple' => true,
					));
			}

		// nouvel utilisateur
		if ($creation === 1) {
			$builder
				->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,
					'invalid_message' => 'Les mots de passe ne sont pas identiques',
					'first_options' => ['label' => 'Mot de passe* : ', 'attr' => ['placeholder' => '*******']],
					'second_options' => ['label' => 'Répétez le mot de passe* : ', 'attr' => ['placeholder' => '*******']],
					'required' => true));


			// édit utilisateur
		} elseif ($creation === 2) {
			$builder
				->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,
					'invalid_message' => 'Les mots de passe ne sont pas identiques',
					'first_options' => ['label' => 'Mot de passe* : ', 'attr' => ['placeholder' => '*******']],
					'second_options' => ['label' => 'Répétez le mot de passe* : ', 'attr' => ['placeholder' => '*******']],
					'required' => false));
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'creation' => null,
			'roles'=>null,
		]);
	}
}
