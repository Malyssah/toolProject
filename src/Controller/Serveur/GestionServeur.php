<?php

namespace App\Controller\Serveur;

use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionServeur extends AbstractController
{

	/**
	 * @Route("serveur",name="list-Serveurs")
	 * @param ServeurRepository $serveurRepository
	 * @return Response
	 */
	public function serveursList(ServeurRepository $serveurRepository)
	{
		$serveurs = $serveurRepository->findAll();

		return $this->render('serveur/serveurs.html.twig', [
			'serveurs' => $serveurs,
		]);
	}

	/**
	 * @Route("/serveur/add", name="add-Serveur")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function addServeur(Request $request, EntityManagerInterface $manager)
	{
		$serveur = new Serveur();
		$form = $this->createForm(ServeurType::class, $serveur, array('creation' => 1));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Serveur $serveur */
			$serveur = $form->getData();
			$manager->persist($serveur);
			$manager->flush();
			$this->addFlash('success', 'Serveur créer !');
			return $this->redirectToRoute('list-Serveurs');
		}
		return $this->render('serveur/add-Serveur.html.twig', array(
			'form' => $form->createView(), 'serveur' => $serveur
		));
	}

	/**
	 * @Route("serveur/{id}", name="edit-Serveur")
	 * @param Serveur $serveur
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function editServeur(Serveur $serveur, Request $request, EntityManagerInterface $manager)
	{
		$form = $this->createForm(ServeurType::class, $serveur);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			/** @var Serveur $serveur */

			$serveur = $form->getData();
			$manager->persist($serveur);
			$manager->flush();

			$this->addFlash('success', 'Serveur modifié !');

			return $this->redirectToRoute('edit-Serveur', array('id' => $serveur->getId()));
		}
		return $this->render('serveur/edit-Serveur.html.twig', array(
			'form' => $form->createView(), 'serveur' => $serveur
		));
	}

	/**
	 * @Route("/serveur/delete/{id}", name="delete-Serveur")
	 * @param Serveur $serveur
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse
	 */
	public function deleteServeur(Serveur $serveur, EntityManagerInterface $manager){
		$manager->remove($serveur);
		$manager->flush();
		$this->addFlash('danger', 'serveur supprimé !');
		return $this->redirectToRoute('list-Serveurs');
	}
}

