<?php

namespace App\Controller\User;

use App\Entity\Alliance;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GestionUser extends AbstractController
{
	/**
	 * @Route("/user", name="list-Users")
	 * @param UserRepository $userRepository
	 * @return Response
	 */
	public function usersList(UserRepository $userRepository)
	{
		$userCourant = $this->getUser();
		$listeUsers = $userRepository->findBy(['serveur' => $userCourant->getServeur()],['username'=>'ASC']);
		return $this->render('user/users.html.twig', [
			'userCourant' => $userCourant,
			'listeUsers' => $listeUsers,
		]);
	}

	/**
	 * @Route("/user/add",name="add-User")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param UserPasswordEncoderInterface $encoder
	 * @return RedirectResponse|Response
	 */
	public function addUser(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
	{
		$userCourant = $this->getUser();
		if ($userCourant){
			$rolesUserCourant = $this->getUser()->getRoles();
		}else{
			$rolesUserCourant = [];
		}
		$user = new User();


		$form = $this->createForm(UserType::class, $user, array('creation' => 1, 'roles' => $rolesUserCourant));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var User $user */
			$user = $form->getData();

			// On récupère le champs serveur qu'on a définit dans le formulaire
			// On doit passer par un $form->get car c'est un champs qui n'appartient pas à l'entité user
			$serveurs = $form->get("serveur")->getData();

			//On encode le mot de passe
			$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($mdpEncoded);
			$user->eraseCredentials();
			$manager->persist($user);
			$manager->flush();

			$this->addFlash('success', 'Utilisateur Ajouté !');
			return $this->redirectToRoute('app_login');
		}
		return $this->render('user/add-User.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/user/{id}", name="edit-User")
	 * @param User|null $user
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param UserPasswordEncoderInterface $encoder
	 * @return RedirectResponse|Response
	 */
	public function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
	{
		$rolesUserCourant = $this->getUser()->getRoles();
		if (!$user) { //si pas d'utilisateur
			$user = new User();
		}
		$oldPeuple = $user->getPeuple();
		$form = $this->createForm(UserType::class, $user, array('creation' => 2, 'roles' => $rolesUserCourant));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var User $user */
			$user = $form->getData();
			$mdpClear = $user->getPlainPassword();
			$serveurs = $form->get("serveur")->getData();
			$peuple = $user->getPeuple();

			if($oldPeuple !== $peuple){
				 $troupe = $user->getTroupes();
				 if ($troupe[0]){
					 $troupe[0]->setCatapulte(null);
					 $troupe[0]->setBelier(null);
					 $manager->persist($troupe[0]);
				 }
			}
			if ($mdpClear) {
				$mdpEncoded = $encoder->encodePassword($user, $mdpClear);
				$user->setPassword($mdpEncoded);
				$user->eraseCredentials();
			}

			/** On persist et flush ici l'objet user */
			$manager->persist($user);
			$manager->flush();
			$this->addFlash('success', 'Utilisateur Modifié avec succès !');
			return $this->redirectToRoute('edit-User', array('id' => $user->getId()));

		}
		return $this->render('user/edit-User.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/user/delete/{id}", name="delete-User")
	 * @param User $user
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse
	 */
	public function deleteUser(User $user, EntityManagerInterface $manager)
	{
		//supprimer l'utilisateur
		$manager->remove($user);
		//execute la requête
		$manager->flush();

		$this->addFlash('danger', 'Utilisateur supprimé !');
		return $this->redirectToRoute('list-Users');
	}

	/**
	 * rejoindre une alliance
	 *
	 * @Route("/user/rejoindreAlliance/{id}", name="rejoindre-Alliance")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param Alliance $alliance
	 * @param User $user
	 * @return RedirectResponse
	 */
	public function addAlliance(Request $request, EntityManagerInterface $manager, Alliance $alliance, User $user){
		if($user){
			$alliance = $this->getUser()->getAlliance();
			if($alliance){
				$user->setAlliance($alliance);
				$manager->persist($user);
				$manager->flush();
			}
		}
		return $this->redirectToRoute('list-Users');
	}

	/**
	 * @Route("/user/quitterAlliance/{id}", name="quitter-Alliance")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param Alliance $alliance
	 * @return RedirectResponse
	 */
	public function quitterAlliance(Alliance $alliance, Request $request, EntityManagerInterface $manager){

		$userCourant = $this->getUser();
		if ($alliance){
			$alliance->removeUser($userCourant);
			$manager->flush();
		}
		$this->addFlash('warning', 'Vous avez quitté votre alliance');
		return $this->redirectToRoute('main');
	}



	/**
	 * @Route("/users-Alliance", name="list-Users-Alliance")
	 * @param UserRepository $userRepository
	 * @return Response
	 */
	public function usersListAlliance(UserRepository $userRepository)
	{
		$userCourant = $this->getUser();
		$listeUsersAlliance = null;
		$alliance = $userCourant->getAlliance();
		if ($alliance){
			$listeUsersAlliance = $userRepository->findBy(['alliance' => $alliance],['username'=>'ASC']);
		}
		return $this->render('user/users-Alliance.html.twig', [
			'userCourant' => $userCourant,
			'listeUsersAlliance' => $listeUsersAlliance,
			'alliance'=>$alliance
		]);
	}

	/**
	 * @Route("/propos", name="propos")
	 */

	public function aPropos()
	{
		return $this->render('propos.html.twig');
	}
}