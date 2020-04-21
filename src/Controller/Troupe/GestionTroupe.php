<?php


namespace App\Controller\Troupe;

use App\Entity\Serveur;
use App\Entity\Troupe;
use App\Entity\User;
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
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param User|null $user
	 * @return RedirectResponse|Response
	 */
	public function editTroupe(User $user, Request $request, EntityManagerInterface $manager)
	{

		$serveurUser = $user->getServeur();
		$peuple = $this->getUser()->getPeuple();
		$acces = false;
		if ($serveurUser and $peuple) {
			$acces = true;
			$troupe = $this->getDoctrine()->getRepository(Troupe::class)->findOneBy(['users' => $user]);
			if (!$troupe) {
				$troupe = new Troupe();
			}


			$form = $this->createForm(TroupeType::class, $troupe, ['peuple' => $peuple]);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				/** @var Troupe $troupe */
				$troupe = $form->getData();
				if ($peuple == 'Romain'){
					$troupe->setPhalange(null);
					$troupe->setDruide(null);
					$troupe->setGourdin(null);
					$troupe->setTeuton(null);
				}elseif($peuple == 'Germain'){
					$troupe->setImperian(null);
					$troupe->setCaesaris(null);
					$troupe->setPhalange(null);
					$troupe->setDruide(null);
				}elseif($peuple == 'Gaulois'){
					$troupe->setGourdin(null);
					$troupe->setTeuton(null);
					$troupe->setImperian(null);
					$troupe->setCaesaris(null);
				}
				$troupe->setUsers($user);
				$troupe->setServeur($serveurUser);

				$manager->persist($troupe);
				$manager->flush();

				$this->addFlash('success', 'Troupes modifiées !');
				return $this->redirectToRoute('edit-troupe', array('id' => $user->getId()));
			}
			return $this->render('troupe/edit-Troupe.html.twig', array(
				'form' => $form->createView(),
				'acces' => $acces
			));
		} else {
			return $this->render('troupe/edit-Troupe.html.twig', array(
				'acces' => $acces
			));
		}


	}


}
