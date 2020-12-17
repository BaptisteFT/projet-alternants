<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/create-user", name="create_user")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles((array) $form->get('role')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_index_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-user/{userId}", name="delete_user")
     */
    public function deleteUser($userId) : Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_index_index');
    }

    /**
     * @Route("/update-user/{userId}", name="update_user")
     */
    public function updateUser(Request $request, $userId){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_index_index');
        }
        return $this->render('registration/modify.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/create-token/{userId}", name="create_token")
     */
    public function createApiToken($userId, UserPasswordEncoderInterface $passwordEncoder){
        $creator = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $user = new User();
        $user->setEmail( "lorem@ipsum");
        $user->setFirstName("lorem");
        $user->setLastName("ispum");
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                "password"
            )
        );
        $user->setRoles((array)"ROLE_COMPANY");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $apiToken = new ApiToken($user, $creator);
        $entityManager->persist($apiToken);
        $entityManager->flush();

        return $this->redirectToRoute('my_profil', array('userId' => $userId));

    }
}
