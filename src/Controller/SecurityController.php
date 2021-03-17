<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/login-token/{token}", name="app_token_login")
     */
    public function tokenLogin($token){
        if ($this->checkToken($token)){
            $entityManager = $this->getDoctrine()->getManager();
            $apiToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);
            $user = $apiToken->getUser();
            return $this->render('security/company-login.hmtl.twig', [
                "token" => $token,
                "tokenUser" => $user
            ]);


        }
        else{
            $this->deleteTokenIfExist($token);
            return $this->render('security/error-token.html.twig');
        }


    }
    /**
     * @Route("/new-company-login/{token}", name="new_company_login")
     */
    public function newCompanyLogin($token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $apiToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);
        $user = $apiToken->getUser();
        $userToken = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get('security.token_storage')->setToken($userToken);
        $this->get('session')->set('_security_main', serialize($userToken));
        $apiToken->setIsActive(true);
        $entityManager->flush();
        /*
        return $this->render('security/debug.html.twig',[
            "apitoken" => $apiToken,
            "user" => $user,

        ]);
        */
        return $this->redirectToRoute('update_company', ['userId' => $user->getId()]);
    }

    /**
     * @Route("/existing-company-login/{token}", name="existing_company_login")
     */
    public function existingCompanyLogin($token)
    {
        $apiToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $_POST['email']]);
        $userToken = new UsernamePasswordToken($user, $_POST['password'], "main", $user->getRoles());
        $this->get('security.token_storage')->setToken($userToken);
        $this->get('session')->set('_security_main', serialize($userToken));
        $entityManager = $this->getDoctrine()->getManager();
        $newToken = new ApiToken($user, $apiToken->getCreator(), true);
        $this->deleteApiToken($apiToken);
        $this->deleteDummyUser($apiToken->getUser());
        $entityManager->persist($newToken);
        $entityManager->flush();
        return $this->redirectToRoute('app_index_index');
    }

    private function deleteTokenIfExist($token)
    {
        $apiToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);
        if ($apiToken == !null){
            if ($apiToken->getIsActive() == false)
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($apiToken);
                $entityManager->flush();
            }
        }
    }
    private function deleteDummyUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($user->getIsActive() == false)
        {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }

    private function deleteApiToken($apiToken)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($apiToken);
        $entityManager->flush();
    }

    private function checkToken($token)
    {
        $res = true;
        $apiToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneBy(['token' => $token]);
        // Vérification si le token existe
        if ($apiToken == null){
            $res = false;
        }
        // Vérification de la date d'expiration
        $timeZone = new \DateTimeZone('Europe/Paris');
        $datetime = new \DateTime();
        $datetime->setTimezone($timeZone);
        if ($apiToken->getExpireDate()->getTimestamp() < $datetime->getTimestamp())
        {
            $res = false;
        }

        // Vérification si le token à déja été utilisé
        if ($apiToken->getIsActive())
        {
            $res = false;
        }

        return $res;

    }

}
