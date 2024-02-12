<?php

namespace App\Story;

use App\Entity\Budget;
use App\Entity\User;
use App\Enum\AccountType;
use App\Factory\AccountFactory;
use App\Factory\BudgetFactory;
use App\Factory\CategoryFactory;
use App\Factory\CategoryGroupFactory;
use App\Factory\PayeeFactory;
use App\Factory\TransactionFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Story;
use function Zenstruck\Foundry\faker;

final class PersonalUserStory extends Story
{
    /**
     * @var array<string, array<int, string>>
     */
    private const array CATEGORIES = [
        'Home' => [
            'Rent',
            'Telus Home',
            'FortisBC',
            'BC Hydro',
            'Sewer and Water',
            'Household',
            'Renters Insurance',
        ],
        'Essentials' => [
            'Support',
            'Car Loan',
            'Auto Gas',
            'Groceries',
            'Koodo',
            'Bank Fees',
            'Medical',
            'Pets',
        ],
        'Entertainment' => [
            'Kelly',
            'Natasha',
            'Dining Out',
            'Fun Money',
        ],
        'Subscriptions' => [
            'Apple Services',
            'You Need a Budget',
            'Britbox',
            'Crave',
            'Paramount+',
            'Nintendo Online',
            'YouTube',
            'Spotify',
            'Twitch',
            'Discord',
        ],
        'Long Term' => [
            'Auto Insurance',
            'Auto Maintenance',
            'Christmas',
            'Vacation',
            'Emergency',
        ],
    ];

    public function build(): void
    {
        $user = UserFactory::createOne([
            'email' => 'steven.bengtson@me.com',
            'name' => 'Steven Bengtson',
            'plainPassword' => 'Pass!234',
        ]);

        $budget = BudgetFactory::createOne([
            'name' => '2024',
            'owner' => $user,
        ]);

        $primaryAccount = AccountFactory::createOne([
            'owner' => $user,
            'budget' => $budget,
            'name' => 'Primary',
            'type' => AccountType::CHECKING,
        ]);
        $secondaryAccount = AccountFactory::createOne([
            'owner' => $user,
            'budget' => $budget,
            'name' => 'Secondary',
            'type' => AccountType::SAVING,
        ]);

        $this->createCategories($user, $budget);
        PayeeFactory::createMany(25, [
            'owner' => $user,
            'budget' => $budget,
        ]);

        TransactionFactory::createMany(500, function () use ($user, $budget, $primaryAccount) {
            return [
                'owner' => $user,
                'budget' => $budget,
                'account' => $primaryAccount,
                'category' => CategoryFactory::random(),
                'payee' => PayeeFactory::random(),
            ];
        });

        TransactionFactory::createMany(300, function () use ($user, $budget, $secondaryAccount) {
            return [
                'owner' => $user,
                'budget' => $budget,
                'account' => $secondaryAccount,
                'category' => CategoryFactory::random(),
                'payee' => PayeeFactory::random(),
            ];
        });
    }

    /**
     * @param User|Proxy<User>     $user
     * @param Budget|Proxy<Budget> $budget
     */
    private function createCategories(User|Proxy $user, Budget|Proxy $budget): void
    {
        foreach (self::CATEGORIES as $categoryGroupName => $categories) {
            $categoryGroup = CategoryGroupFactory::createOne([
                'owner' => $user, 'budget' => $budget, 'name' => $categoryGroupName,
            ]);

            foreach ($categories as $name) {
                CategoryFactory::createOne([
                    'owner' => $user,
                    'budget' => $budget,
                    'categoryGroup' => $categoryGroup,
                    'name' => $name,
                ]);
            }
        }
    }
}
