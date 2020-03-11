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
	 * @Route("/user/create",name="create-user")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $encoder
	 * @return RedirectResponse|Response
	 */
	public function createUser(Request $request, UserPasswordEncoderInterface $encoder)
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
			$entitymanager = $this->getDoctrine()->getManager();
			$entitymanager->persist($user);
			$entitymanager->flush();
			$this->addFlash('success', 'Utilisateur Ajouté !');

			return $this->redirectToRoute('main');
		}

		return $this->render('user/add-user.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/user/list", name="list-users")
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
	 * @Route("/user/edit-user/{id}", name="edit")
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

			if ($user === array('creation' => 1)) {
				$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
				$user->setPassword($mdpEncoded);
				$user->eraseCredentials();
			} else {
				$user = $form->getData();

				$manager->persist($user);
				$manager->flush();
				$this->addFlash('success', 'Utilisateur Modifié avec succès !');
				return $this->redirectToRoute('list-users');
			}
		}
		return $this->render('user/edit.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/user/delete-user/{id}", name="delete")
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