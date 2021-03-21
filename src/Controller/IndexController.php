<?php


namespace App\Controller;


use App\Entity\ApiToken;
use App\Entity\JobInfo;
use App\Entity\Notification;
use App\Entity\StudentToken;
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
            $apitokens= $this->getDoctrine()->getRepository(ApiToken::class)->findBy(["user" => $user, "isActive" => true]);
            $students = [];
            $researchStudents = [];
            foreach ($apitokens as $token)
            {
                if ($token->getCreator()->getStatus() == "RESEARCH"){
                    array_push($researchStudents, $token->getCreator());
                }
                elseif ($token->getCreator()->getStatus() == "CONTRACT_SEND" || $token->getCreator()->getStatus() == "WORKING" ){
                    array_push($students,$token->getCreator());
                }
            }
            return $this->render("/main/index.html.twig", [
                'researchStudents' => $researchStudents,
                'user' => $user,
                'students' => $students,
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
        $jobinfos = $this->getDoctrine()->getRepository(JobInfo::class)->findByStudent($user->getId());
        $studentToken = $this->getDoctrine()->getRepository(StudentToken::class)->find($user->getId());


        return $this->render("main/profil.html.twig", [
            'user' => $user,
            'infos' => $jobinfos,
            'token' => $token,
            'studentToken' => $studentToken,
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
        $file = $_FILES['file-selector']['tmp_name'];
        $teacher = $this->getDoctrine()->getRepository(User::class)->findUserByName($_POST['teacher-select']);
        $fileToString = file_get_contents($file);
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
        $workContract = new WorkContract($student, base64_encode($fileToString));
        $student->setTeacher($teacher);
        $student->setStatus("WORKING");
        $logAdmin = new Notification("L'étudiant ".$student->getFirstName()." ".$student->getLastName()." est désormais en alternance","student-working",1);
        $logTutor = new Notification("Vous avez été assigné pour être le tuteur de ".$student->getFirstName()." ".$student->getLastName(),"tutor-assign",1);
        $logTutor->addUser($teacher);
        $adminList =  $this->getDoctrine()->getRepository(User::class)->findAllAdmin();
        foreach ($adminList as $admin){
            $logAdmin->addUser($admin);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($logAdmin);
        $entityManager->persist($logTutor);
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