<?php


namespace App\Form;


use App\Entity\Alliance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllianceEditType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
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
			))
			->add('Modifier', SubmitType::class);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Alliance::class,
		]);
	}
}