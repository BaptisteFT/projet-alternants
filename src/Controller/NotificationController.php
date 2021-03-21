<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Notification;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/notification-backlog/{userId}" , name="notification_backlog")
     */
    public function notificationBacklog($userId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $backlog = $this->findNotificationByUserId($userId);
        return $this->render("/main/notification-backlog.html.twig", [
            'backlog' => $backlog,
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
        return $this->redirectToRoute('notification_backlog');

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
        return $this->redirectToRoute('notification_backlog');
    }

    private function findNotificationByUserId($userId){
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $notificationsByUser = [];
        foreach ($notifications as $notif){
            foreach ($notif->getUser() as $user){
                if ($user->getId() == $userId){
                    array_push($notificationsByUser, $notif);
                }
            }
        }
        return $notificationsByUser;

    }

    public function notificationNumber($userId){
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $notificationsCount = 0;
        foreach ($notifications as $notif){
            foreach ($notif->getUser() as $user){
                if ($user->getId() == $userId){
                    $notificationsCount += 1;
                }
            }
        }
        return $notificationsCount;
    }
}