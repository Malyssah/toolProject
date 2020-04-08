<?php

namespace App\Controller\User;

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
		$users = $userRepository->findAll();
		return $this->render('user/users.html.twig', [
			'users' => $users,
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
		$rolesUserCourant = $this->getUser()->getRoles();
		$user = new User();

		$form = $this->createForm(UserType::class, $user, array('creation' => 1,'roles'=>$rolesUserCourant));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var User $user */
			$user = $form->getData();
			// On récupère le champs serveur qu'on a définit dans le formulaire
			// On doit passer par un $form->get car c'est un champs qui n'appartient pas
			// à l'entité userServeurPeuple, c'est un champs non-mappé
			$serveurs = $form->get("serveur")->getData();

			//On encode le mot de passe
			$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($mdpEncoded);
			$user->eraseCredentials();
			$manager->persist($user);
			$manager->flush();

			$this->addFlash('success', 'Utilisateur Ajouté !');
			return $this->redirectToRoute('list-Users');
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
		$form = $this->createForm(UserType::class, $user, array('creation' => 2, 'roles'=>$rolesUserCourant));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var User $user */
			$user = $form->getData();
			$mdpClear = $user->getPlainPassword();
			$serveurs = $form->get("serveur")->getData();

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
}