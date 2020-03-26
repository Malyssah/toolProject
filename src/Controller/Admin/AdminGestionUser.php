<?php


namespace App\Controller\Admin;


use App\Entity\ServeurUserPeuple;
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

class AdminGestionUser extends AbstractController
{
	/**
	 * @Route("admin/user", name="admin-list-Users")
	 * @param UserRepository $userRepository
	 * @return Response
	 */
	public function AdminUsersList(UserRepository $userRepository)
	{
		$role = $this->getUser()->getRoles();
		if (in_array('ROLE_ADMIN', $role)) {
			$users = $userRepository->findAll();
			return $this->render('admin/user/admin-Users.html.twig', [
				'users' => $users,
			]);
		} else {
			$this->addFlash('danger', 'page reservé admin!');
			return $this->redirectToRoute('main');
		}

	}

	/**
	 * @Route("admin/user/delete/{id}", name="admin-delete-User")
	 * @param User $user
	 * @param EntityManagerInterface $manager
	 * @return RedirectResponse
	 */
	public function AdminDeleteUser(User $user, EntityManagerInterface $manager)
	{
		$manager->remove($user);
		$manager->flush();
		$this->addFlash('danger', 'Utilisateur supprimé !');
		return $this->redirectToRoute('list-Users');
	}
}