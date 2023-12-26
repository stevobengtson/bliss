<?php

namespace App\Enums;

enum AccountType: int
{
    case CHECKING = 1;
    case SAVINGS = 2;
    case LOAN = 3;
    case CREDIT_CARD = 4;
    case LINE_OF_CREDIT = 5;
}
