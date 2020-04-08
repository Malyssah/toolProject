<?php

namespace App\Controller\Serveur;

use App\Entity\User;
use App\Repository\ServeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionServeur extends AbstractController
{
	/**
	 * @Route("/",name="main")
	 * @return Response
	 */
	public function main()
	{
		$userCourant = $this->getUser();
		$serveurs = $userCourant->getServeur();


		return $this->render('main.html.twig', [
			'serveurs'=>$serveurs
		]);
	}

	/**
	 * @Route("/serveur/{id}",name="accueil-serveur")
	 * @param ServeurRepository $serveurRepository
	 * @param null $id
	 * @return Response
	 */
	public function accueilServeur(ServeurRepository $serveurRepository, $id = null){
		$serveur = $serveurRepository->findOneBy(['id'=>$id]);
	    //Prendre les infos du serveur et les afficher dans une vue

        return $this->render('main.html.twig',[
        	'serveur'=>$serveur
		]);
	}
	}

