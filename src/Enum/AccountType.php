<?php

namespace App\Enum;

enum AccountType: string
{
    case CHECKING = "Checking";
    case SAVING = "Saving";
    case CREDIT_CARD = "Credit Card";
    case LOAN = "Loan";
    case LINE_OF_CREDIT = "Line of Credit";
}
