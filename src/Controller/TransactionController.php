<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class TransactionController extends AbstractController
{
    #[Route('/account/{account_id}/transaction', name: 'app_transaction_index', methods: ['GET'])]
    public function index(
        #[MapEntity(id: 'account_id')]
        Account $account,
        TransactionRepository $transactionRepository,
        #[MapQueryParameter]
        int $page = 1,
        #[MapQueryParameter]
        int $itemsPerPage = 25
    ): Response {
        $queryBuilder = $transactionRepository->createQueryBuilder('t');
        $queryBuilder->select('t')
            ->where('t.account = ?1')
            ->setParameter(1, $account->getId()->toRfc4122());

        // TODO: Add ordering
        $queryBuilder->orderBy('t.entryDate', 'desc');

        $paginator = new Paginator($queryBuilder->getQuery());

        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);

        $paginator
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($page-1))
            ->setMaxResults($itemsPerPage);

        return $this->render('transaction/index.html.twig', [
            'transactions' => $paginator,
            'account' => $account,
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
            'pagesCount' => $pagesCount
        ]);
    }

    #[Route('/account/{account_id}/transaction/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(id: 'account_id')]
        Account $account,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setBudget($account->getBudget());

        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', ['account_id' => $account->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
            'account' => $account
        ]);
    }

    #[Route('transaction/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', ['account_id' => $transaction->getAccount()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('transaction/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_index', ['account_id' => $transaction->getAccount()->getId()], Response::HTTP_SEE_OTHER);
    }
}
