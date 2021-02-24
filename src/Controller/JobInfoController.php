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
     * @Route("/jobinfo/{Id}", name="job_info")
     */
    public function show_JobInfo($Id): \Symfony\Component\HttpFoundation\Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_STUDENT') || $this->container->get('security.authorization_checker')->isGranted('ROLE_TEACHER')) {
            $student = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $Id]);
            $jobinfos = $this->getDoctrine()->getRepository(JobInfo::class)->findByStudent($student->getFirstName());
            return $this->render("job_info/show-jobinfo.html.twig", [
                'student' => $student,
                'infos' => $jobinfos,
            ]);}
        // Partie ADMIN
        else if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $jobinfos = $this->getDoctrine()->getRepository(JobInfo::class)->findAll();
            return $this->render('job_info/show-jobinfo.html.twig', [
                'infos' => $jobinfos]);
        }else{
            return $this->render('job_info/show-jobinfo.html.twig');
        }
    }

    /**
     * @Route("/create-jobinfo/{studentId}", name="create_jobinfo")
     */
    public function createJobInfo(Request $request, $studentId): Response
    {
        $jobinfo = new JobInfo();
        $form = $this->createForm(JobInfoFormType::class, $jobinfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $jobinfo->setStudent($form->get('student')->getData());
            $jobinfo->setTitle($form->get('title')->getData());
            $jobinfo->setDescription($form->get('description')->getData());

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($jobinfo);
            $entityManager->flush();


            return $this->redirectToRoute('job_info', [ 'Id'=> $studentId]);
        }

        return $this->render('job_info/create-jobinfo.html.twig', [
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
        return $this->redirectToRoute('job_info', [ 'Id'=>$user->getId()]);
    }

    /**
     * @Route("/update-jobinfo/{jobinfoId}", name="update_jobinfo")
     */
    public function updateJobInfo(Request $request, $jobinfoId){
        $jobinfo = $this->getDoctrine()->getRepository(JobInfo::class)->find($jobinfoId);
        $form = $this->createForm(JobInfoFormType::class, $jobinfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $user = $this->getUser();
            return $this->redirectToRoute('job_info', [ 'Id'=>$user->getId()]);
        }
        return $this->render('job_info/modify-jobinfo.html.twig', [
            'jobinfoForm' => $form->createView(),
            'infos' => $jobinfo,
        ]);

    }
}
