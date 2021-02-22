<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Notification;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/notification-backlog" , name="notification-backlog")
     */
    public function notificationBacklog()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $backlog = $this->getDoctrine()->getRepository(Notification::class)->findNewNotification();
        $archivedBacklog = $this->getDoctrine()->getRepository(Notification::class)->findArchivedNotification();
        return $this->render("/main/notification-backlog.html.twig", [
            'backlog' => $backlog,
            'archivedBacklog' => $archivedBacklog,
        ]);
    }

    /**
     * @Route("/delete-notification/{logId}" , name="delete-notification")
     */
    public function deleteNotification($logId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $log = $this->getDoctrine()->getRepository(Notification::class)->find($logId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($log);
        $entityManager->flush();
        return $this->redirectToRoute('notification-backlog');

    }

    /**
     * @Route("/archive-notification/{logId}" , name="archive-notification")
     */
    public function archiveNotification($logId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $log = $this->getDoctrine()->getRepository(Notification::class)->find($logId);
        $log->setIsArchived(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('notification-backlog');
    }
}