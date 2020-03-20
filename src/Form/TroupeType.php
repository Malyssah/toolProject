<?php


namespace App\Form;


use App\Entity\Troupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TroupeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$peuple = $options['peuple']; //option creation pour les 3 != peuple
		$builder
			->add('belier', IntegerType::class, array(
				'label' => 'belier: '
			))
			->add('catapulte', IntegerType::class, array(
				'label' => 'catapulte: '
			))
			->add('Enregistrer', SubmitType::class);

		if ($peuple === 'ROMAIN') {
			$builder
				->add('imperian', IntegerType::class, array(
					'label' => 'imperian: '
				))
				->add('caesaris', IntegerType::class, array(
					'label' => 'caesaris: '
				));
		}
		if ($peuple === 'GAULOIS') {
			$builder
				->add('phalange', IntegerType::class, array(
					'label' => 'phalange: '
				))
				->add('druide', IntegerType::class, array(
					'label' => 'druide: '
				));
		}
		if ($peuple === 'GERMAIN') {
			$builder
				->add('gourdin', IntegerType::class, array(
					'label' => 'gourdin: '
				))
				->add('teuton', IntegerType::class, array(
					'label' => 'teuton: '
				));
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Troupe::class,
			'peuple' => null,
		]);
	}
}