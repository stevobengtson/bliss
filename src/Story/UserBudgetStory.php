<?php

namespace App\Story;

use App\Enums\AccountType;
use App\Factory\AccountFactory;
use App\Factory\TransactionFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserBudgetStory extends Story
{
    public function build(): void
    {
        $owner = UserFactory::createOne([
            'email' => 'steven.bengtson@me.com',
            'name' => 'Steven Bengtson',
            'plainPassword' => 'Pass!234',
        ]);

        AccountFactory::createOne([
            'nickname' => 'Primary',
            'balance' => 0,
            'startingBalance' => 0,
            'type' => AccountType::CHECKING,
            'owner' => $owner,
        ]);
        AccountFactory::createOne([
            'nickname' => 'Savings',
            'balance' => 0,
            'startingBalance' => 0,
            'type' => AccountType::SAVINGS,
            'owner' => $owner,
        ]);
        AccountFactory::createOne([
            'nickname' => 'Visa',
            'balance' => 0,
            'startingBalance' => 0,
            'type' => AccountType::CREDIT_CARD,
            'owner' => $owner,
        ]);
        AccountFactory::createOne([
            'nickname' => 'Credit Line',
            'balance' => 0,
            'startingBalance' => 0,
            'type' => AccountType::LINE_OF_CREDIT,
            'owner' => $owner,
        ]);

        TransactionFactory::createMany(100, function (int $index) {
            return [
                'account' => AccountFactory::random()->disableAutoRefresh(),
            ];
        });

    }
}
