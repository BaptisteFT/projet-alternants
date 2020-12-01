<?php


namespace App\Controller;


use App\Entity\Contract;
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
     * @Route("/contract-form" , name="contract_form")
     */
    public function contractForm(Request $request): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractFormType::class, $contract);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('contract_pdf', ['contractId' => $contract->getId()]);
        }

        return $this->render('contractForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contract-pdf/{contractId}" , name="contract_pdf")
     */
    public function editPDF($contractId) : Response
    {
        $contract = $this->getDoctrine()->getRepository(Contract::class)->find($contractId);
        return $this->render('pdfViewer.html.twig', [
            'contract' => $contract
        ]);
    }
}