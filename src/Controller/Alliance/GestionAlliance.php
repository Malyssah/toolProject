<?php

namespace App\Controller\Alliance;

use App\Entity\Alliance;
use App\Form\AllianceType;
use App\Repository\AllianceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionAlliance extends AbstractController
{

	/**
	 * @Route("/alliance/create",name="add_Alliance")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */

	public function createAlliance(Request $request, EntityManagerInterface $manager)
	{
		$alliance = new Alliance();
		$form = $this->createForm(AllianceType::class, $alliance);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Alliance $alliance */
			$alliance = $form->getData();
			$manager->persist($alliance);
			$manager->flush();
			$this->addFlash('success', 'Groupe crée avec succès !');
			return $this->redirectToRoute('main');
		}
		return $this->render('alliance/add_Alliance.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("alliance/list",name="list-alliances")
	 * @param AllianceRepository $allianceRepository
	 * @return Response
	 */

	public function alliancesList(AllianceRepository $allianceRepository)
	{
		$alliances = $allianceRepository->findAll();

		return $this->render('alliance/alliances.html.twig', [
			'alliances' => $alliances,
		]);
	}
}