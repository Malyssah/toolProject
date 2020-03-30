<?php


namespace App\Controller\LostPassword;


use App\Entity\User;
use App\Form\NewLostPasswordType;
use App\Form\UserType;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LostPassword extends AbstractController
{
	/**
	 * @Route("/reset-password", name="reset-password")
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param Swift_Mailer $mailer
	 * @return Response
	 * @throws Exception
	 */
	public function resetPassword(Request $request, EntityManagerInterface $manager, Swift_Mailer $mailer)
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
				$dateAjout = new DateTime(date('Y-m-d H:i:s'));
				$dateExpire = new DateTime(date('Y-m-d H:i:s'));
				$dateExpire = $dateExpire->add(new DateInterval('PT5M'));

				$lostPassword->setDateAjout($dateAjout);
				$lostPassword->setDateExpire($dateExpire);
				$lostPassword->setUser($user);
				$lostPassword->setToken($token);

				$message = (new Swift_Message('ProjectTools - Mot de passe oublié '));
				$message->setFrom('malysah.dev@gmail.com');
				$message->setTo($email);
				$message->setBody(
					$this->renderView('security/email-mdp-perdu.html.twig', array('token' => $token, 'user' => $user)
					),
					'text/html'
				);
				if ($mailer->send($message)) {
					$manager->persist($lostPassword);
					$manager->flush();
					$this->addFlash('success', "L'e-mail a bien été envoyé");
				} else {
					$this->addFlash('danger', "Erreur lors de l'envoi. Veuillez verifier votre adresse e-mail");
				}

				//si ok
				$this->redirectToRoute('reset-password');
			} else {
				//pas ok
				$this->addFlash('danger', "Email inconnu dans notre base de donnée; Merci de verifier votre adresse e-mail");
				$this->redirectToRoute(('reset-password'));

			}
		}
		return $this->render('security/lost_password.html.twig');
	}

	/**
	 * @Route("/new-password", name="new-password")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $encoder
	 * @return RedirectResponse|Response
	 * @throws Exception
	 */
	public function newPassword(Request $request, UserPasswordEncoderInterface $encoder)
	{
		$credentials = false;
		$token = $request->query->get('token');
		$idUser = $request->query->get('id');

		if ($token AND $idUser) { //si token et utilisateur ok
			$repository = $this->getDoctrine()->getRepository(\App\Entity\LostPassword::class);
			/** @var \App\Entity\LostPassword $lostPassword * */
			$lostPassword = $repository->findOneBy(['token' => $token, 'user' => $idUser]);

			if ($lostPassword) {
				$currentDate = new DateTime(date('Y-m-d H:i:s'));
				// Si la date courante est antérieur à la lostPassword
				if ($currentDate <= $lostPassword->getDateExpire()) {
					$credentials = true; // si identifiant ok, on affiche le formulaire
					$user = $lostPassword->getUser();
					$form = $this->createForm(NewLostPasswordType::class, $user);
					$form->handleRequest($request);
					if ($form->isSubmitted() && $form->isValid()) {
						$user = $form->getData();
						//on encode le mot de passe
						$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
						$user->setPassword($mdpEncoded);
						$user->eraseCredentials();
						$em = $this->getDoctrine()->getManager();
						$em->persist($user);
						$em->flush();

						//On passe le token à null
						$lostPassword->setToken(null);

						$em->persist($lostPassword);
						$em->flush();
						return $this->redirectToRoute('password-valid');
					}
					return $this->render('security/new-password.html.twig',array(
						'form'=>$form->createView(),
						'credentials'=>$credentials,
					));
				}
			}
		}
		return $this->render('security/new-password.html.twig',array(
			'credentials'=>$credentials
		));
	}

	/**
	 * @Route("/password-valid/{id}", name="password-valid")
	 * @return Response
	 */
	public function passwordValid()
	{
		return $this->render('security/password_valid.html.twig');
	}

}
