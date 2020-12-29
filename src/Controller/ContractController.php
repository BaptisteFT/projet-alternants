<?php


namespace App\Controller;


use App\Entity\Contract;
use App\Entity\User;
use App\Form\ContractFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('/contract/pdfViewer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contract-pdf/{contractId}" , name="contract_pdf")
     */
    public function editPDF($contractId) : Response
    {
        $contract = $this->getDoctrine()->getRepository(Contract::class)->find($contractId);
        return $this->render('/contract/pdfViewer.html.twig', [
            'contract' => $contract
        ]);
    }
}