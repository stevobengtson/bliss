<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Security\AccountVoter;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account')]
class AccountController extends AbstractController
{
    use UserTrait;

    public function __construct(private readonly AccountService $accountService)
    {
    }

    #[Route('/', name: 'app_account_index', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $itemsPerPage = 25
    ): Response {
        $user = $this->getCurrentUser();
        $results = $this->accountService->getAccountsForUser($user, $page, $itemsPerPage);

        return $this->render('account/index.html.twig', [
            'accounts' => (array) $results,
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getCurrentUser();

        $account = new Account();
        $account->setOwner($user);
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountService->saveAccount($account);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    #[IsGranted(AccountVoter::VIEW, 'account')]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    #[IsGranted(AccountVoter::EDIT, 'account')]
    public function edit(Request $request, Account $account): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountService->saveAccount($account);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    #[IsGranted(AccountVoter::DELETE, 'account')]
    public function delete(Request $request, Account $account): Response
    {
        if ($this->isCsrfTokenValid('delete'.$account->getId(), $request->request->get('_token'))) {
            $this->accountService->removeAccount($account);
        }

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
