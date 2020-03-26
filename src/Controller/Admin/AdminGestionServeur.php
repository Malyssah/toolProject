<?php


namespace App\Controller\Admin;


use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGestionServeur extends AbstractController
{
	/**
	 * @Route("/admin/serveur",name="admin-List-Serveurs")
	 * @param ServeurRepository $serveurRepository
	 * @return Response
	 */
	public function adminServeursList(ServeurRepository $serveurRepository)
	{
		$role = $this->getUser()->getRoles();
		if (in_array('ROLE_ADMIN', $role)) {
			$serveurs = $serveurRepository->findAll();

			return $this->render('admin/serveur/admin-Serveurs.html.twig', [
				'serveurs' => $serveurs,
			]);
		}else{
			$this->addFlash('danger', 'page reservé admin!');
			return $this->redirectToRoute('main');
		}
	}

	/**
	 * @Route("admin/serveur/add", name="admin-Add-Serveur")
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
			return $this->redirectToRoute('admin-List-Serveurs');
		}
		return $this->render('admin/serveur/admin-Add-Serveur.html.twig', array(
			'form' => $form->createView(), 'serveur' => $serveur
		));
	}

	/**
	 * @Route("admin/serveur/{id}", name="admin-Edit-Serveur")
	 * @param Serveur $serveur
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse|Response
	 */
	public function AdminEditServeur(Serveur $serveur, Request $request, EntityManagerInterface $manager)
	{
		$form = $this->createForm(ServeurType::class, $serveur);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var Serveur $serveur */

			$serveur = $form->getData();
			$manager->persist($serveur);
			$manager->flush();

			$this->addFlash('success', 'Serveur modifié !');

			return $this->redirectToRoute('admin-Edit-Serveur', array('id' => $serveur->getId()));
		}
		return $this->render('admin/serveur/admin-Edit-Serveur.html.twig', array(
			'form' => $form->createView(), 'serveur' => $serveur
		));
	}

	/**
	 * @Route("admin/serveur/delete/{id}", name="admin-Delete-Serveur")
	 * @param Serveur $serveur
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse
	 */
	public function deleteServeur(Serveur $serveur, EntityManagerInterface $manager)
	{
		//cascade sur troupe
		$manager->remove($serveur);
		$manager->flush();
		$this->addFlash('danger', 'serveur supprimé !');
		return $this->redirectToRoute('admin-List-Serveurs');
	}
}