<?php


namespace App\Controller;


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
        $this->denyAccessUnlessGranted('ROLE_USER');
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $students = $this->findByStudents($users);
        return $this->render("index.html.twig", [
            'students' => $students,
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