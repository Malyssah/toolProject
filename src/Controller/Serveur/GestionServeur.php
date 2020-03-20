<?php

namespace App\Controller\Serveur;

use App\Repository\ServeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionServeur extends AbstractController
{
		// TODO : Faire le listing des serveur en fonction de l'utilisateur courant sur une page
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

}

