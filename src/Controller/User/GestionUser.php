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
		$user = new User();
		$form = $this->createForm(UserType::class, $user, array('creation' => 1));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var User $user */
			$user = $form->getData();
			$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($mdpEncoded);
			$user->eraseCredentials();
			$manager->persist($user);
			$manager->flush();
			$this->addFlash('success', 'Utilisateur Ajouté !');

			return $this->redirectToRoute('list-Users');
		}

		return $this->render('user/add-user.html.twig', array(
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
		if (!$user) { //si pas d'utilisateur
			$user = new User();
		}
		$form = $this->createForm(UserType::class, $user, array('creation' => 2));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var User $user */
			$user = $form->getData();
			$mdpClear = $user->getPlainPassword();

			if ($mdpClear) {
				$mdpEncoded = $encoder->encodePassword($user, $mdpClear);
				$user->setPassword($mdpEncoded);
				$user->eraseCredentials();
			}


			$manager->persist($user);
			$manager->flush();
			$this->addFlash('success', 'Utilisateur Modifié avec succès !');
			return $this->redirectToRoute('edit-User', array('id' => $user->getId()));

		}
		return $this->render('user/edit-user.html.twig', array(
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
		$manager->remove($user);
		$manager->flush();
		$this->addFlash('danger', 'Utilisateur supprimé !');
		return $this->redirectToRoute('main');
	}
}