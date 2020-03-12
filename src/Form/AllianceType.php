<?php


namespace App\Form;


use App\Entity\Alliance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllianceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$creation = $options['creation']; //option creation pour l'ajout et edit

			$builder
				->add('name', TextType::class, array(
					'label' => 'Nom: '
				))
				->add('cadran', ChoiceType::class, array(
					'label' => 'cadran: ',
					'placeholder' => 'Sélectionnez votre cadran',
					'choices' => array(
						'SO' => 'SO',
						'NO' => 'NO',
						'SE' => 'SE',
						'NE' => 'SE'
					),
				));

		if ($creation === 1) {
			$builder
				->add('Enregistrer', SubmitType::class);
		}else{
			$builder
				->add('Modifier', SubmitType::class);
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Alliance::class,
			'creation' => null,
		]);
	}
}