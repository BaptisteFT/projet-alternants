<?php

namespace App\Controller;

use App\Entity\ApiToken;
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
            $userToken = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
            $this->get('security.token_storage')->setToken($userToken);
            $this->get('session')->set('_security_main', serialize($userToken));
            $apiToken->setIsActive(true);
            $entityManager->flush();
            //$this->deleteApiToken($apiToken);
        }
        if ($user->getIsActive()){
            return $this->redirectToRoute('app_index_index');
        }
        else{
            return $this->redirectToRoute('update_company', ['userId' => $user->getId()]);
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

        return $res;

    }

}
