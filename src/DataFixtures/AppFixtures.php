<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Story\PersonalUserStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new([
            'email' => 'steven.bengtson+admin@me.com',
            'name' => 'Steven Bengtson (SA)',
            'plainPassword' => 'sup3rUs3rP@ss',
        ])->asSuperAdmin()->create();

        PersonalUserStory::load();
    }
}
