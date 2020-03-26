<?php


namespace App\Controller\LostPassword;


use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LostPassword extends AbstractController
{
	/**
	 * @Route("/reset-password", name="reset-password")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param MailerInterface $mailer
	 * @return Response
	 * @throws Exception
	 * @throws TransportExceptionInterface
	 */
	public function resetPassword(Request $request, EntityManagerInterface $manager, MailerInterface $mailer)
	{
		$email = $request->get('Email');
		if ($email) { // je vérifie l'existance de l'email user
			$user = $this->getDoctrine()->getRepository(User::class)
				->findOneBy(['email' => $email]);

			// Si l'email existe
			if ($user) {
				//je crée le nouvel objet password
				$lostPassword = new \App\Entity\LostPassword();

				//Générer un identifiant unique ... true pour 23 caractères
				$token = hash('sha256', uniqid(rand(), true));

				//Générer la limite de temps du token
				$dateAjout = new DateTime(date('Y-m-d H:i:s', strtotime('now')));
				$dateExpire = new DateTime(date('Y-m-d H:i:s'));

				//todo : Ajouter 5 min a date
				$dateExpire = $dateAjout-> add(new \DateInterval('P5M'));

				//todo : Set le user récupéré et les dates
				//$lostPassword->setDateAjout(new \DateTime($dateAjout));
				//$lostPassword->setDateExpire(new\DateTime($dateExpire));
				$lostPassword->setDateAjout($dateAjout);
				$lostPassword->setDateExpire($dateExpire);
				$lostPassword->setUser($user);
				$lostPassword->setToken($token);

				$email = (new TemplatedEmail())
					->from('admincoi@fimeco.fr')
					->to($email)
					->subject('ToolProject - Mot de passe oublié')
					->htmlTemplate('/security/email-mdp-perdu.html.twig')
					->context([
						'token' => $token,
						'user' => $user
					]);

				$mailer->send($email);

				//$manager->persist($lostPassword);
				//$manager->flush();

				//si ok
				$this->addFlash('success', "Mail envoyé");
				$this->redirectToRoute(('reset-password'));
			} else {
				//pas ok
				$this->addFlash('danger', "Email inconnu dans notre base de donnée; Merci de verifier votre adresse e-mail");
				$this->redirectToRoute(('reset-password'));

			}
		}
		return $this->render('security/lost_password.html.twig');
	}
}
