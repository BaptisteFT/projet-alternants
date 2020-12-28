<?php


namespace App\Controller;


use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        // SECURITE
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Partie ADMIN
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            $students = $this->findByStudents($users);
            return $this->render("index.html.twig", [
                'students' => $students,
            ]);

        }

        // Partie COMPANY
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_COMPANY'))
        {
            $user = $this->getUser();
            $apitoken= $this->getDoctrine()->getRepository(ApiToken::class)->findOneByUser($user->getId());
            $creator = $apitoken->getCreator();
            return $this->render("index.html.twig", [
                'user' => $user,
                'tokenCreator' => $creator
            ]);
        }

        // Partie OTHER
        return $this->render("index.html.twig");
    }

    /**
     * @Route("/profil/{userId}", name="my_profil")
     */
    public function myProfil($userId): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $token = $this->getDoctrine()->getRepository(ApiToken::class)->findOneByCreator($userId);
        return $this->render("profile.html.twig", [
            'user' => $user,
            'token' => $token,
        ]);
    }

    private function findByStudents($users) {
        $students = [];
        foreach ($users as $user ) {
            $userRole = $user->getRoles();
            if (in_array("ROLE_STUDENT", $userRole))
            {
            array_push($students, $user);
            }
        }
        return $students;
    }
}