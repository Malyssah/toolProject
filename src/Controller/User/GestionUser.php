<?php


namespace App\Controller\User;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GestionUser extends AbstractController
{

	/**
	 * @Route("/user/create",name="create-user")
	 */
	public function createUser(Request $request, UserPasswordEncoderInterface $encoder){
		$user = new User();
		$form = $this->createForm(UserType::class,$user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			/** @var User $user */
			$user = $form->getData();
			$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($mdpEncoded);
			$user->eraseCredentials();
			$entitymanager = $this->getDoctrine()->getManager();
			$entitymanager->persist($user);
			$entitymanager->flush();
			$this->addFlash('success','Utilisateur Ajouté !');

			return $this->redirectToRoute('main');
		}

		return $this->render('user/add-user.html.twig',array(
			'form'=>$form->createView(),
		));
	}

	/**
	 * @Route("/user/list", name="list-users")
	 * @param UserRepository $users
	 * @return Response
	 */
	public function usersList(UserRepository $users)
	{
		return $this->render('user/users.html.twig', [
			'users' => $users->findAll(),
		]);
	}
}