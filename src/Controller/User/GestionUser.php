<?php


namespace App\Controller\User;


use App\Entity\Serveur;
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

			// On récupère le champs serveur qu'on a définit dans le formulaire
			// On doit passer par un $form->get car c'est un champs qui n'appartient pas
			// à l'entité userServeurPeuple, c'est un champs non-mappé
			$serveurs = $form->get("serveur")->getData();
			$mdpEncoded = $encoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($mdpEncoded);
			$user->eraseCredentials();

			$manager->persist($user);
			$manager->flush();


			// On set les deux attributs serveur et user de l'entité ServeurUserPeuple séparement
			if ($serveurs) {
				foreach ($serveurs as $serveur) {
					// on instancie une nouvelle entité ServeurUserPeuple
					$serveurUserPeuple = new ServeurUserPeuple();
					$serveurUserPeuple->setServeur($serveur);
					$serveurUserPeuple->setUser($user);
					//On oublie pas de persiste les deux objets créés
					$manager->persist($serveurUserPeuple);
					$manager->flush();
				}
			}
			$this->addFlash('success', 'Utilisateur Ajouté !');

			return $this->redirectToRoute('list-Users');
		}

		return $this->render('user/add-User.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/user/{idServeur}/{id}", name="edit-User")
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
		$serveurs = $user->getServeurUserPeuples();
		$form = $this->createForm(UserType::class, $user, array('creation' => 2, 'serveurs' => $serveurs));
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
			/** On récupère les anciens serveurs en BDD et les nouveaux cochés
			 *    On en fait un tableau de chaque avec l'id du serveur en index et le peuple en valeur
			 *    pour l'un et le nom du serveur pour mieux se repéré pour l'autre
			 */
			$oldsServeurUserPeuple = $user->getServeurUserPeuples()->getValues();
			foreach ($oldsServeurUserPeuple as $value) {
				$oldServeur [$value->getServeur()->getId()] = $value->getPeuple();
			}
			foreach ($serveurs as $serveur) {
				$idServeurs [$serveur->getId()] = $serveur->getName();
			}

			/** Traitement des anciens serveurs cochés*/

			$sameServeurs = array_intersect_key($idServeurs, $oldServeur);
			/** Si il y a des serveurs qui sont restés cochés on les parcours
			 * et on récupère leur id dans un tableau
			 */
			if ($sameServeurs) {
				while (current($sameServeurs)) {
					$idOldServeurs[] = key($sameServeurs);
					next($sameServeurs);
				}
			}
			$setOldServeurs = $this->getDoctrine()->getRepository(ServeurUserPeuple::class)->findBy(['serveur' => $idOldServeurs, 'user' => $user]);

			/** On va chercher les anciens serveur puis on les supprimes de la table */
			$serveursRemove = $this->getDoctrine()->getRepository(ServeurUserPeuple::class)->findBy(['user' => $user]);
			foreach ($serveursRemove as $serveurRemove) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($serveurRemove);
				$em->flush();
			}

			/** On parcours les ancien serveurs encore chochés et on les set avec
			 * l'attribut Peuple si il a été renseigné si non il sera set a null*/
			if ($setOldServeurs) {
				foreach ($setOldServeurs as $setOldServeur) {
					// on instancie une nouvelle entité ServeurUserPeuple
					$serveurUserPeuple = new ServeurUserPeuple();
					$serveurUserPeuple->setServeur($setOldServeur->getServeur());
					$serveurUserPeuple->setUser($user);
					if ($setOldServeur->getPeuple()) {
						$serveurUserPeuple->setPeuple($setOldServeur->getPeuple());
					}
					//On oublie pas de persiste les deux objets créés
					$manager->persist($serveurUserPeuple);
					$manager->flush();
				}
			}

			/** Traitement des nouveaux serveur cochés */
			$newServeurs = array_diff_key($idServeurs, $oldServeur);
			/** Si on détect des nouveaux serveur on les parcours
			 * et on enregistre leur id dans un tableau
			 */
			if ($newServeurs) {
				while (current($newServeurs)) {
					$idNewServeurs[] = [key($newServeurs)];
					next($newServeurs);
				}
			}
			/** on enregistre les nouveaux serveurs en bdd
			 * Pas de notion de peuple ici
			 */
			$setNewServeurs = $this->getDoctrine()->getRepository(Serveur::class)->findBy(['id' => $idNewServeurs]);
			if ($setNewServeurs) {
				foreach ($setNewServeurs as $setNewServeur) {
					$serveurUserPeuple = new ServeurUserPeuple();
					$serveurUserPeuple->setServeur($setNewServeur);
					$serveurUserPeuple->setUser($user);
					//On oublie pas de persiste les deux objets créés
					$manager->persist($serveurUserPeuple);
					$manager->flush();
				}
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