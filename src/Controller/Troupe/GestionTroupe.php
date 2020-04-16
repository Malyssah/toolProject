<?php


namespace App\Controller\Troupe;

use App\Entity\Serveur;
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
	 * @Route("/troupe/{id}", name="edit-troupe")
	 * @return RedirectResponse|Response
	 */
	public function editTroupe(Request $request, EntityManagerInterface $manager)
	{

		return $this->render('troupe/edit-Troupe.html.twig', array(
		));
	}

	/**
	 * @Route("/troupe/ajax/form",name="ajax-form-troupe")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return Response
	 */
	public function ajaxFormTroupe(Request $request, EntityManagerInterface $manager){
		$utilisateurCourant= $this->getUser();
		$peuple = $request->get('peuple');
		$troupe = new Troupe();
		$form = $this->createForm(TroupeType::class, $troupe, array('peuple'=>$peuple));
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Troupe $troupe */
			$troupe = $form->getData();


			$troupe->setServeur($utilisateurCourant->getServeur());
			$troupe->setUsers($utilisateurCourant);


			$manager->flush();
			$this->addFlash('success', 'Troupe crée avec succès !');
//			$this->redirectToRoute('edit-troupe',[
//				'id'=>$utilisateurCourant->getId(),
//				'peuple'=>$peuple
//			]);
		}

		return $this->render('troupe/ajax-form-troupe.html.twig', [
			'form'=>$form->createView(),
			'peuple'=>$peuple,
		]);
	}


}
