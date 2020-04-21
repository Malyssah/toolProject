<?php

namespace App\Controller\Alliance;

use App\Entity\Alliance;
use App\Entity\User;
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
		//recup l'utilisateur courant
		$utilisateurCourant = $this->getUser();
		$idAllianceUtilisateurCourant = $this->getUser()->getAlliance()->getid();
		$serveurUtilisateurCourant = $utilisateurCourant->getServeur();
		$alliances = $allianceRepository->findBy(['serveur' => $serveurUtilisateurCourant]);

		return $this->render('alliance/alliances.html.twig', [
			'alliances' => $alliances,
			'idAlliance'=> $idAllianceUtilisateurCourant

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
		//si utilisateur courant à un serveur
		$userCourant = $this->getUser();
		$serveurs = $userCourant->getServeur();
		if (!isset($serveurs)) {
			//on refuse et on l'invit à choisir un serveur
			$this->addFlash('warnig', 'Il faut être rattaché à un serveur afin de pouvoir créer un groupe !');
			return $this->redirectToRoute('edit-User', ['id' => $userCourant->getId()]);
		} else {
			//il peux creer un groupe

			$alliance = new Alliance();
			$form = $this->createForm(AllianceType::class, $alliance, array('creation' => 1));
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				/** @var Alliance $alliance */

				$alliance = $form->getData();
				//todo: rattacher l'utilisateur qui créé l'alliance
				//$createurAlliance = $userCourant->getId->$this->addAlliance();
				//$createurAlliance = $userCourant->getId()
				//$createurAlliance = $userCourant->get("alliance")->getData();
				//$createurAlliance = $userCourant->$this->addAlliance()->getData();
				//$alliance->$this->addUser()->getData();
				//$user->setAlliance($this);
				//$usersAlliance = $alliance->addUser( $usersAlliance);

				//$chefAlliance = $this->getUser()->addUser();
				//$chefAlliance->$this->setAlliance();

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
	 * Modifier une alliance
	 *
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

	/**
	 * Ajouter un utilisateur à l'alliance
	 *
	 * @Route("/alliance/addMembre/{id}", name="add-Membre-Alliance")
	 * @param Alliance $alliance
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse
	 */
	public function addMembreAlliance(Alliance $alliance, Request $request, EntityManagerInterface $manager)
	{
		if ($alliance) {
			$utilisateurCourant = $this->getUser();
			if ($utilisateurCourant) {
				$utilisateurCourant->setAlliance($alliance);
				$manager->persist($utilisateurCourant);
				$manager->flush();
			}
		}
		return $this->redirectToRoute('list-Alliances');
	}
}