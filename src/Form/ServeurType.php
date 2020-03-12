<?php


namespace App\Form;


use App\Entity\Serveur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServeurType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$creation = $options['creation']; //option creation pour l'ajout et edit

			$builder
				->add('name', TextType::class, array(
					'label' => 'nom du serveur: '
				));

		if ($creation === 1) {
			$builder
				->add('Ajouter', SubmitType::class);
		}else{
			$builder
			->add('Modifier', SubmitType::class);
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Serveur::class,
			'creation' => null,
		]);
	}
}