<?php


namespace App\Controller;

use App\Entity\Notification;
use App\Form\ReviewFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Review;

/**
 * @Route("/reviews")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/review/{Id}", name="review")
     */
    public function show_review($Id): \Symfony\Component\HttpFoundation\Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_STUDENT')) {
            $student = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $Id]);
            $reviews = $this->getDoctrine()->getRepository(Review::class)->findByStudent($student);
            return $this->render("review/show-review.html.twig", [
                'student' => $student,
                'reviews' => $reviews,
            ]);}
        //TUTEUR
        else if ($this->container->get('security.authorization_checker')->isGranted('ROLE_TEACHER')) {
            $teacher = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $Id]);
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
            $review->setContentTwo($form->get('contentTwo')->getData());
            $review->setContentThree($form->get('contentThree')->getData());
            $review->setContentFour($form->get('contentFour')->getData());
            $review->setDate($form->get('date')->getData());

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($review);
            $notif = new Notification("Votre tuteur ".$author->getLastName()." ".$author->getFirstName()." vous à assigné une note","review", 2);
            $notif->addUser($student);
            $entityManager->persist($notif);
            $entityManager->flush();


            return $this->redirectToRoute('review', [ 'Id'=> $teacherId]);
        }

        return $this->render('review/create-review.html.twig', [
            'reviewForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-review/{reviewId}", name="delete_review")
     */
    public function deleteReview($reviewId) : Response
    {
        $review = $this->getDoctrine()->getRepository(Review::class)->find($reviewId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($review);
        $entityManager->flush();
        $user = $this->getUser();
        return $this->redirectToRoute('review', [ 'Id'=>$user->getId()]);
    }

    /**
     * @Route("/update-review/{reviewId}", name="update_review")
     */
    public function updateUser(Request $request, $reviewId){
        $review = $this->getDoctrine()->getRepository(Review::class)->find($reviewId);
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $user = $this->getUser();
            return $this->redirectToRoute('review', [ 'Id'=>$user->getId()]);
        }
        return $this->render('review/modify-review.html.twig', [
            'reviewForm' => $form->createView(),
            'review' => $review,
        ]);

    }
}