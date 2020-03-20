<?php


namespace App\Controller\LostPassword;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LostPassword extends AbstractController
{
	/**
	 * @Route("/reset-password", name="reset-password")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return Response
	 */
	public function resetPassword(Request $request, EntityManagerInterface $manager)
	{
		$email = $request->request->get('email');

		// je vérifie l'existance de l'email
		if ($email) {
			$email = $this->getDoctrine()->getManager();
			$repository = $email->getRepository(User::class)
				->findOneBy(['email' => $email]);
			$user = $repository->searchEmail($email);
			//Si l'email existe
			if ($user) {
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($resetPassword);
				$manager->flush();

				$this->addFlash('success', "ok");
				$this->redirectToRoute(('reset-password'));
			}
			$this->addFlash('danger', "Pas bon");
			return $this->render('lost_password/lost_password.html.twig');
		}
	}
}
