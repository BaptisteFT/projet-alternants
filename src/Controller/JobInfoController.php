<?php


namespace App\Controller;

use App\Entity\JobInfo;
use App\Entity\User;
use App\Form\JobInfoFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class JobInfoController extends AbstractController
{

    /**
     * @Route("/create-jobinfo/{studentId}", name="create_jobinfo")
     */
    public function createJobInfo(Request $request, $studentId): Response
    {
        $jobinfo = new JobInfo();
        $form = $this->createForm(JobInfoFormType::class, $jobinfo);
        $form->handleRequest($request);
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);

        if ($form->isSubmitted() && $form->isValid()) {

            $jobinfo->setStudent($student);
            $jobinfo->setCompanyName($form->get('companyName')->getData());
            $jobinfo->setGoal($form->get('goal')->getData());
            $jobinfo->setContext($form->get('context')->getData());
            $jobinfo->setTechnology($form->get('technology')->getData());
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($jobinfo);
            $entityManager->flush();


            return $this->redirectToRoute('my_profil', [ 'userId' => $studentId]);
        }

        return $this->render('job-info/create-job-info.html.twig', [
            'jobinfoForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-jobinfo/{jobinfoId}", name="delete_jobinfo")
     */
    public function deleteJobInfo($jobinfoId) : Response
    {
        $jobinfo = $this->getDoctrine()->getRepository(JobInfo::class)->find($jobinfoId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($jobinfo);
        $entityManager->flush();
        $user = $this->getUser();
        return $this->redirectToRoute('my_profil', [ 'userId' => $user->getId()]);
    }

    /**
     * @Route("/update-jobinfo/{jobinfoId}", name="update_jobinfo")
     */
    public function updateJobInfo(Request $request, $jobinfoId){
        $jobinfo = $this->getDoctrine()->getRepository(JobInfo::class)->find($jobinfoId);
        $student = $this->getDoctrine()->getRepository(User::class)->findByJobInfo($jobinfo->getId());
        $form = $this->createForm(JobInfoFormType::class, $jobinfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('my_profil', [ 'userId' => $jobinfo->getStudent()->getId()]);
        }
        return $this->render('job-info/modify-job-info.html.twig', [
            'jobinfoForm' => $form->createView(),
            'infos' => $jobinfo,
        ]);

    }
}