<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Group entry fixture: when group "users" is loaded, dependencies will be loaded first.
 */
class UsersGroupFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // intentionally empty — dependencies will be executed before this fixture
    }

    public function getDependencies(): array
    {
        return [
            \App\Domain\Admin\Fixtures\AdminFixtures::class,
            \App\Domain\Member\Fixtures\MemberFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['users'];
    }
}
