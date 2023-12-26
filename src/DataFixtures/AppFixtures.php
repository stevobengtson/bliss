<?php

namespace App\DataFixtures;

use App\Story\UserBudgetStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserBudgetStory::load();

        $manager->flush();
    }
}
