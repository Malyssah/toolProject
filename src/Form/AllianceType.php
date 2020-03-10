<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AllianceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name', TextType::class, array(
				'label' => 'Nom: '
			))
			->add('cadran', TextType::class, array(
				'label' => 'cadran: '
			))
			->add('Enregistrer', SubmitType::class);

	}
}


