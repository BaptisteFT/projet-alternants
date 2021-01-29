<?php


namespace App\Controller;


use App\Entity\ApiToken;
use App\Entity\User;
use App\Entity\WorkContract;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            return $this->render("/main/index.html.twig", [
                'students' => $students,
            ]);

        }

        // Partie COMPANY
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_COMPANY'))
        {
            //$students = $this->getDoctrine()->getRepository(User::class)->findStudentsInResearch();
            $user = $this->getUser();
            $apitoken= $this->getDoctrine()->getRepository(ApiToken::class)->findOneByUser($user->getId());
            $creator = $apitoken->getCreator();
            return $this->render("/main/index.html.twig", [
                //'students' => $students,
                'user' => $user,
                'tokenCreator' => $creator
            ]);
        }

        // Partie TEACHER
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_TEACHER'))
        {
            $teacherUserName = $this->getUser()->getUsername();
            $teacher =  $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $teacherUserName]);
            $students = $this->getDoctrine()->getRepository(User::class)->findStudentsByTeacher($teacher);
            return $this->render("/main/index.html.twig", [
                "students" => $students,
                "teacher" => $teacher,
            ]);
        }

        // Partie Student
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_STUDENT'))
        {
            return $this->render("/main/index.html.twig");
        }
        //
    }



    /**
     * @Route("/profil/{userId}", name="my_profil")
     */
    public function myProfil($userId): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $token = $this->getDoctrine()->getRepository(ApiToken::class)->findOneByCreator($userId);
        return $this->render("main/profil.html.twig", [
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @Route("/admin" , name="admin_panel")
     */
    public function adminPanel()
    {
        // SECURITE
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render("/main/admin.html.twig", [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/validate-work/{studentId}" , name="validate_work")
     */
    public function validateWork($studentId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $teachers = $this->findByTeachers($users);
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
        return $this->render("/main/validateWork.html.twig", [
            'student' => $student,
            'teachers' => $teachers,
        ]);
    }

    /**
     * @Route("/upload-work-contract/{studentId}" , name="upload_work_contract")
     */
    public function uploadWorkContract(Request $request, $studentId){
        //$filename = $_FILES['file-selector']['name'];
        //$size = $_FILES['file-selector']['size'];
        $file = $_FILES['file-selector']['tmp_name'];
        $teacher = $this->getDoctrine()->getRepository(User::class)->findUserByName($_POST['teacher-select']);
        $fileToString = file_get_contents($file);
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
        $workContract = new WorkContract($student, base64_encode($fileToString));
        $student->setTeacher($teacher);
        $student->setStatus("WORKING");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($workContract);
        $entityManager->flush();

        return $this->redirectToRoute('app_index_index');
    }

    /**
     * @Route("/view-work-contract/{studentId}" , name="view_work_contract")
     */
    public function viewWorkContract($studentId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $workContract = $this->getDoctrine()->getRepository(WorkContract::class)->findOneByStudent($studentId);
        return $this->render("/main/viewWorkContract.html.twig", [
            'workContract' => $workContract,
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

    private function findByTeachers($users) {
        $teachers = [];
        foreach ($users as $user ) {
            $userRole = $user->getRoles();
            if (in_array("ROLE_TEACHER", $userRole))
            {
                array_push($teachers, $user);
            }
        }
        return $teachers;
    }
}