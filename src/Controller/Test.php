<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Test extends AbstractController
{

	/**
	 * @Route("/test",name="test-page")
	 */
	public function main()
	{
		$maVariable = 'MonPrémon';
		return $this->render('security/test.html.twig', [
			'maVariable' => $maVariable,
		]);
	}
}