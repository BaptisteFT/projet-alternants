<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\ContractBase64;
use App\Entity\JobInfo;
use App\Entity\Notification;
use App\Entity\Review;
use App\Entity\StudentToken;
use App\Entity\User;
use App\Entity\WorkContract;
use App\Form\CompanyFormType;
use App\Form\RegistrationFormType;
use App\Service\GrantedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SimpleXLSX;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/create-user", name="create_user")
     */
    public function createUser(Request $request,GrantedService $grantedService, UserPasswordEncoderInterface $passwordEncoder): Response
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
            if ($this->isRoleStudent($grantedService,$user))
            {
                $user->setStatus("RESEARCH");
            }
            else{
                $user->setStatus("OTHER");
            }
            $user->setIsActive(true);

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

    private function isRoleStudent(GrantedService $grantedService,$user){
        $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());
        if ($grantedService->isGranted($user, 'ROLE_STUDENT')) {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @Route("/delete-user/{userId}", name="delete_user")
     */
    public function deleteUser($userId) : Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $entityManager = $this->getDoctrine()->getManager();
        $this->safeDeleteUser($userId);
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_index_index');
    }

    private function safeDeleteUser($userId){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $contract = $user->getContract();
        if ($contract != null){
            $contractBase64 = $this->getDoctrine()->getRepository(ContractBase64::class)->findOneByContract($contract->getId());
            if ($contractBase64 != null){
                $entityManager->remove($contractBase64);
                $entityManager->remove($contract);
            }
            else{
                $entityManager->remove($contract);
            }
        }
        $workContract = $this->getDoctrine()->getRepository(WorkContract::class)->findOneByStudent($userId);
        if ($workContract != null){
            $entityManager->remove($workContract);
        }
        $companyToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneByCreator($userId);
        if ($companyToken != null){
            $entityManager->remove($companyToken);
        }
        $studentToken = $user->getStudentToken();
        if ($studentToken != null){
            $entityManager->remove($studentToken);
        }
        $jobInfos = $this->getDoctrine()->getRepository(JobInfo::class)->findByStudent($userId);
        foreach ($jobInfos as $jobInfo){
            $entityManager->remove($jobInfo);
        }
        $notifications = $user->getNotifications();
        foreach ($notifications as $notif){
            $entityManager->remove($notif);
        }
        $reviews = $user->getReviews();
        foreach ($reviews as $review){
            $entityManager->remove($review);
        }
        $entityManager->flush();
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
     * @Route("/update-company/{userId}", name="update_company")
     */
    public function updateCompany(Request $request, $userId, UserPasswordEncoderInterface $passwordEncoder){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $form = $this->createForm(CompanyFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->getDoctrine()->getManager()->flush();
            $user->setIsActive(true);
            $user->setStatus("OTHER");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_index_index');

        }
        return $this->render('registration/modify-company.html.twig', [
            'companyForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/update-student-password/{userId}", name="update_student_password")
     */
    public function updateStudentPassword($userId, UserPasswordEncoderInterface $passwordEncoder){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $_POST['password']
                )
            );
        $studentToken =$this->getDoctrine()->getRepository(StudentToken::class)->find($user->getStudentToken()->getId());
        $studentToken->setIsActive(true);
        $userToken = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get('security.token_storage')->setToken($userToken);
        $this->get('session')->set('_security_main', serialize($userToken));
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('app_index_index');
    }

    /**
     * @Route("/create-token/{userId}", name="create_token")
     */
    public function createApiToken($userId, UserPasswordEncoderInterface $passwordEncoder){
        $this->deletePreviousApiToken($userId);
        $creator = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $user = new User();
        $user->setEmail( bin2hex(random_bytes(4))."@".bin2hex(random_bytes(4)));
        $user->setFirstName("");
        $user->setLastName("");
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                "password"
            )
        );
        $user->setRoles((array)"ROLE_COMPANY");
        $user->setIsActive(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $apiToken = new ApiToken($user, $creator, false);
        $entityManager->persist($apiToken);
        $entityManager->flush();


        return $this->redirectToRoute('my_profil', array('userId' => $userId));

    }

    /**
     * @Route("/excel-parse", name="excel_parse")
     */
    public function xlxCreateUsers(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $file = $request->files->get('file-selector');
        if ($xlsx = SimpleXLSX::parse($file)) {

            $ra = $xlsx->rowsEx();
            $stutemp = json_encode($ra);
            $students = array();
            foreach ($ra as $value) {
                $user = new User();
                if ($value[11]["value"] != "" && filter_var($value[11]["value"], FILTER_VALIDATE_EMAIL)) {
                    $student = array("name" => json_encode($value[2]["value"]), "surname" => json_encode($value[3]["value"]), "email" => json_encode($value[11]["value"]));//3,10

                    $user->setFirstName(substr($student["name"], 1, -1));
                    $user->setLastName(substr($student["surname"], 1, -1));
                    $user->setEmail(substr($student["email"], 1, -1));
                    $user->setRoles(array('ROLE_STUDENT'));
                    $user->setIsActive(true);
                    $user->setStatus("RESEARCH");
                    $generator = new ComputerPasswordGenerator();

                    $generator
                        ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
                        ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
                        ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
                        ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false);

                    $password = $generator->generatePassword();
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $password
                        )
                    );
                    array_push($students, $student);
                    $entityManager = $this->getDoctrine()->getManager();
                    if (!$this->studentAlreadyExist($user)){
                        $entityManager->persist($user);
                        $studentToken = new StudentToken($user);
                        $entityManager->persist($studentToken);
                        $entityManager->flush();
                    }
                }
            }
            $students = json_encode($students);
        } else {
            echo SimpleXLSX::parseError();
        }
        if (isset($ra)) {
            return $this->render('registration/excelParse.html.twig');
        } else {
            return $this->render('registration/excelParse.html.twig');
        }
    }

    private function deletePreviousApiToken($userId)
    {

        $apiToken = $this->getDoctrine()->getRepository(ApiToken::class)->findOneByCreator($userId);
        if ($apiToken != null){
            $user = $apiToken->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($apiToken);
            $entityManager->remove($user);
            $entityManager->flush();
        }

    }

    private function studentAlreadyExist($user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepo = $entityManager->getRepository(User::class);
        if ($userRepo->findOneBy(['email' => $user->getEmail()])) {
            return true;
        }
        else{
            return false;
        }
    }


}
