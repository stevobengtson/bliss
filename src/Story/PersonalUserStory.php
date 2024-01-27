<?php

namespace App\Story;

use App\Entity\Budget;
use App\Entity\CategoryGroup;
use App\Entity\User;
use App\Enum\AccountType;
use App\Factory\AccountFactory;
use App\Factory\BudgetFactory;
use App\Factory\CategoryFactory;
use App\Factory\CategoryGroupFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class PersonalUserStory extends Story
{
    /**
     * @var array<string, array<string>>
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
        ]
    ];

    public function build(): void
    {
        $user = UserFactory::createOne([
            'email' => 'steven.bengtson@me.com',
            'name' => 'Steven Bengtson',
            'plainPassword' => 'Pass!234'
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

        $categoryGroups = CategoryGroupFactory::new([
            ['owner' => $user, 'budget' => $budget]
        ])->sequence([
            ['name' => 'Home'],
            ['name' => 'Essentials'],
            ['name' => 'Entertainment'],
            ['name' => 'Subscriptions'],
            ['name' => 'Long Term'],
        ])->create();

        CategoryFactory::new([
            ['owner' => $user, 'budget' => $budget, 'categoryGroup' => $categoryGroups[0]]
        ])->sequence([
            ['name' => 'Rent'],
            ['name' => 'Telus Home'],
            ['name' => 'FortisBC'],
            ['name' => 'BC Hydro'],
            ['name' => 'Sewer and Water'],
            ['name' => 'Household'],
            ['name' => 'Renters Insurance'],
        ])->create();
    }

    private function createCategories(User $user, Budget $budget): void
    {
        foreach (self::CATEGORIES as $categoryGroup => $categories) {

        }
    }
}
