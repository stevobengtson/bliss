<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route('/transaction/{id}', name: 'app_transaction')]
    public function index(Account $account): Response
    {
        return $this->render('transaction/index.html.twig', [
            'account' => $account,
        ]);
    }
}
