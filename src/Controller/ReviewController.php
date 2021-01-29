<?php


namespace App\Controller;

use App\Form\ReviewFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Review;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/{teacherId}/{studentId}", name="review")
     */
    public function show_review($teacherId, $studentId): \Symfony\Component\HttpFoundation\Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_STUDENT')) {
            $student = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $studentId]);
            $reviews = $this->getDoctrine()->getRepository(Review::class)->findByStudent($student);
            return $this->render("review/show-review.html.twig", [
                'student' => $student,
                'reviews' => $reviews,
            ]);}
        //TUTEUR
        else if ($this->container->get('security.authorization_checker')->isGranted('ROLE_TEACHER')) {
            $teacher = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $teacherId]);
            $reviews = $this->getDoctrine()->getRepository(Review::class)->findByAuthor($teacher);
            return $this->render("review/show-review.html.twig", [
                'teacher' => $teacher,
                'reviews' => $reviews,
            ]);}
        // Partie ADMIN
        else if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();
            return $this->render('review/show-review.html.twig', [
                'reviews' => $reviews]);
        }else{
            return $this->render('review/show-review.html.twig');
        }
    }
    /**
     * @Route("/create-review/{teacherId}/{studentId}", name="create_review")
     */
    public function createReview(Request $request, $teacherId, $studentId): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $teacherId]);
            $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
            $review->setAuthor($author);
            $review->setStudent($student);
            $review->setRating($form->get('rating')->getData());
            $review->setContent($form->get('content')->getData());
            $review->setDate($form->get('date')->getData());

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($review);
            //$author->addReview($review);
            $entityManager->flush();


            return $this->redirectToRoute('review', ['teacherId'=> $teacherId, 'studentId'=> $studentId]);
        }

        return $this->render('review/create-review.html.twig', [
            'reviewForm' => $form->createView(),
        ]);
    }
    /*


    public function addAction(User $student, Request $request)
    {
        $review = new Review();

        $form = $this->createForm(new ReviewFormType()
        $form->handleRequest($request);

        if ($form->isValid()) {
            $review->setAuthor($this->getUser());
            $review->setApproved(false);
            $review->setProduct($student);

            /** @var ReviewService reviewService */
    /*
            $reviewService = $this->container->get('ReviewService');
            $reviewService->save($review);

            return $this->redirect($this->generateUrl('product_view', array(
                'id' => $student->getId(),
            )));
        }

        return $this->render('AppBundle:Review:add.html.twig', array(
            'form' => $form->createView(),
            'student' => $student
        ));
    }
*/
}