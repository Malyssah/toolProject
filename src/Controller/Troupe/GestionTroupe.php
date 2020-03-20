<?php


namespace App\Controller\Troupe;

use App\Entity\ServeurUserPeuple;
use App\Entity\Troupe;
use App\Form\TroupeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionTroupe extends AbstractController
{
	/**
	 * @Route("/troupe/add", name="add-Troupe")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function addTroupe(Request $request, EntityManagerInterface $manager)
	{
		$userCourant = $this->getUser();
		$serveurUserPeuple = $this->getDoctrine()->getRepository(ServeurUserPeuple::class)->findOneBy(['user'=>$userCourant]);
		$peuple = $serveurUserPeuple->getPeuple();
		$troupe = new Troupe();
		$form = $this->createForm(TroupeType::class, $troupe, array('peuple' => $peuple));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Troupe $troupe */
			$troupe = $form->getData();

			//TODO: Rattaché l'utilisateur et le serveur à l'entité troupe
			// Les informations sont accéssible via $serveurUserPeuple

			$manager->persist($troupe);
			$manager->flush();
			$this->addFlash('success', 'Troupe crée avec succès !');
			return $this->redirectToRoute('edit-troupe');
		}
		return $this->render('troupe/add-Troupe.html.twig', array(
			'form' => $form->createView(),
			'troupe' => $troupe,
		));
	}

	/**
	 * @Route("/troupe/{id}", name="edit-troupe")
	 * @param Troupe $troupe
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function editTroupe(Troupe $troupe, Request $request, EntityManagerInterface $manager)
	{
		$form = $this->createForm(TroupeType::class, $troupe);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Troupe $troupe */
			$troupe = $form->getData();
			$manager->persist($troupe);
			$manager->flush();
			$this->addFlash('success', 'troupe modifié !');
			return $this->redirectToRoute('edit-troupe', array('id' => $troupe->getId()));
		}
		return $this->render('troupe/edit-Troupe.html.twig', array(
			'form' => $form->createView(), 'troupe' => $troupe
		));
	}
}
