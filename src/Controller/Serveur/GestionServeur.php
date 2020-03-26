<?php

namespace App\Controller\Serveur;

use App\Entity\ServeurUserPeuple;
use App\Repository\ServeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		$idUser = $this->getUser()->getId();
		$serveurs = $this->getDoctrine()->getRepository(ServeurUserPeuple::class)->findBy(['user'=>$idUser]);
		foreach ($serveurs as $serveur){
			$tab [] = $serveur->getServeur()->getName();
		}
//		dd($tab);
		$serveurs = $serveurRepository->findAll();

		return $this->render('serveur/serveurs.html.twig', [
			'serveurs' => $serveurs,
		]);
	}

}

