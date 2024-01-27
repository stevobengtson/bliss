<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Payee;
use App\Form\PayeeType;
use App\Repository\PayeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class PayeeController extends AbstractController
{
    #[Route('/budget/{budget_id}/payee', name: 'app_payee_index', methods: ['GET'])]
    public function index(
        #[MapEntity(id: 'budget_id')]
        Budget $budget,
        PayeeRepository $payeeRepository
    ): Response {
        $payees = $payeeRepository->findBy(['budget' => $budget]);

        return $this->render('payee/index.html.twig', [
            'payees' => $payees,
            'budget' => $budget,
        ]);
    }

    #[Route('/budget/{budget_id}/payee/new', name: 'app_payee_new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(id: 'budget_id')]
        Budget $budget,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $payee = new Payee();
        $payee->setBudget($budget);

        $form = $this->createForm(PayeeType::class, $payee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payee);
            $entityManager->flush();

            return $this->redirectToRoute('app_payee_index', ['budget_id' => $budget->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payee/new.html.twig', [
            'payee' => $payee,
            'budget' => $budget,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payee_show', methods: ['GET'])]
    public function show(Payee $payee): Response
    {
        return $this->render('payee/show.html.twig', [
            'payee' => $payee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payee $payee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PayeeType::class, $payee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payee/edit.html.twig', [
            'payee' => $payee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payee_delete', methods: ['POST'])]
    public function delete(Request $request, Payee $payee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payee->getId(), $request->request->get('_token'))) {
            $entityManager->remove($payee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payee_index', [], Response::HTTP_SEE_OTHER);
    }
}
