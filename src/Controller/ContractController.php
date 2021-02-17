<?php


namespace App\Controller;


use App\Entity\ApiToken;
use App\Entity\Contract;
use App\Entity\ContractBase64;
use App\Entity\User;
use App\Form\ContractFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contract")
 */
class ContractController extends AbstractController
{
    /**
     * @Route("/contract-form/{userId}" , name="contract_form")
     */
    public function contractForm(Request $request, $userId): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $contract = new Contract();
        $form = $this->createForm(ContractFormType::class, $contract);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contract->setUser($user);
            $entityManager->persist($contract);
            $user->setContract($contract);
            $entityManager->flush();

            return $this->redirectToRoute('contract_pdf', ['contractId' => $contract->getId()]);
        }

        return $this->render('/contract/contractForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/update-contract-form/{userId}" , name="update_contract_form")
     */
    public function updateContractForm(Request $request, $userId): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $contract = $user->getContract();
        $form = $this->createForm(ContractFormType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('contract_pdf', ['contractId' => $contract->getId()]);
        }

        return $this->render('/contract/contractForm.html.twig', [
            'form' => $form->createView(),

        ]);

    }

    /**
     * @Route("/contract-pdf/{contractId}" , name="contract_pdf")
     */
    public function editPDF($contractId) : Response
    {
        $contract = $this->getDoctrine()->getRepository(Contract::class)->find($contractId);
        $user = $contract->getUser();

        return $this->render('/contract/pdfViewer.html.twig', [
            'contract' => $contract,
        ]);
    }

    /**
     * @Route("/validate-pdf/{contractId}" , name="validate_pdf")
     */
    public function validatePDF($contractId) : Response
    {
        $contract = $this->getDoctrine()->getRepository(Contract::class)->find($contractId);
        $contractBase64 = $this->getDoctrine()->getRepository(ContractBase64::class)->findOneByContract($contractId);
        $contract->getUser()->setStatus("CONTRACT_SEND");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('send_pdf',['contractBase64Id' => $contractBase64->getId()]);

    }


    /**
     * @Route("/save-pdf/{contractId}" , name="save_pdf")
     */
    public function savePDF($contractId)
    {
        $this->deletePreviousContractBase64($contractId);
        $base64String = file_get_contents("php://input");
        $contract = $this->getDoctrine()->getRepository(Contract::class)->find($contractId);
        $contractBase64 = new ContractBase64($contract,$base64String);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contractBase64);
        $entityManager->flush();
        return new Response(
            'INSERT OK',
            Response::HTTP_OK
        );
    }

    private function deletePreviousContractBase64($contractId)
    {
        $contractBase64 =  $this->getDoctrine()->getRepository(ContractBase64::class)->findOneByContract($contractId);
        if ($contractBase64 != null){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contractBase64);
            $entityManager->flush();
        }
    }

    /**
     * @Route("/send-pdf/{contractBase64Id}" , name="send_pdf")
     */
    public function sendEmail(MailerInterface $mailer,$contractBase64Id)
    {
        $contractBase64 =  $this->getDoctrine()->getRepository(ContractBase64::class)->find($contractBase64Id);
        $email = (new Email())
            ->from('projet.alternants.usmb@gmail.com')
            ->to('projet.alternants.usmb@gmail.com')
            ->subject('Convention Alternant M2')
            ->text('Bonjour ci-joint la convention pré-remplie')
            ->attach(base64_decode($contractBase64->getBase64()), 'pre-convention.pdf', 'application/pdf');
        $mailer->send($email);

        return $this->redirectToRoute('app_index_index');
    }
}