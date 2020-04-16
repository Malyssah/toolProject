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
	 * @Route("alliance",name="list-Alliances")
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

	/**
	 * @Route("/alliance/add",name="add-Alliance")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function addAlliance(Request $request, EntityManagerInterface $manager)
	{
		//si utilisateur courant à un serveur soit !isset  ou  is null
		$userCourant = $this->getUser();
		$serveurs = $userCourant->getServeur();
		if (!isset($serveurs)) {
			//on refuse et on l'invit à voisir un serveur
			$this->addFlash('warnig', 'Il faut être rattaché à un serveur afin de pouvoir créer un groupe !');
			return $this->redirectToRoute('edit-User',[ 'id'=>$userCourant->getId()]);
		} else {
			//il peux creer un groupe

			$alliance = new Alliance();
			$form = $this->createForm(AllianceType::class, $alliance, array('creation' => 1));
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				/** @var Alliance $alliance */
				//todo: rattaché l'utilisateur qui créé l'alliance
				

				$alliance = $form->getData();
				$manager->persist($alliance);
				$manager->flush();
				$this->addFlash('success', 'Groupe crée avec succès !');
				return $this->redirectToRoute('list-Alliances');
			}
			return $this->render('alliance/add-Alliance.html.twig', array(
				'form' => $form->createView(), 'alliance' => $alliance
			));
		}
	}

	/**
	 * @Route("alliance/{id}", name="edit-Alliance")
	 * @param Alliance $alliance
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function editAlliance(Alliance $alliance, Request $request, EntityManagerInterface $manager)
	{
		$form = $this->createForm(AllianceType::class, $alliance);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Alliance $alliance */

			$alliance = $form->getData();
			$manager->persist($alliance);
			$manager->flush();

			$this->addFlash('success', 'Groupe modifié !');

			return $this->redirectToRoute('edit-Alliance', array('id' => $alliance->getId()));
		}
		return $this->render('alliance/edit-Alliance.html.twig', array(
			'form' => $form->createView(), 'alliance' => $alliance
		));
	}

	/**
	 * @Route("/alliance/delete/{id}", name="delete-Alliance")
	 * @param Alliance $alliance
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse
	 */
	public function deleteAlliance(Alliance $alliance, EntityManagerInterface $manager)
	{
		$manager->remove($alliance);
		$manager->flush();
		$this->addFlash('danger', 'Groupe supprimé !');
		return $this->redirectToRoute('list-Alliances');
	}
}