<?php

namespace App\Domain\Admin\Fixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Domain\User\Entity\User;
use App\Domain\Admin\Entity\Admin;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Geo\Repository\GeoRegionRepository;
use App\Domain\Admin\Entity\AdminProfile;
use App\Domain\Geo\Fixtures\GeoContinentAndRegionFixtures;

/**
 * Fixture for admins.
 */
class AdminFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly GeoRegionRepository $regionRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admins = [
            [
                'email' => 'admin@example.com',
                'password' => '12345678aZ!',
                'nickname' => 'admin',
            ],
        ];

        $continentName = 'Europe';
        $regionName = 'Western';
        /** @var \App\Repository\GeoRegionRepository $region */
        $region = $manager->getRepository(GeoRegion::class);
        $adminRegion = $region->regionByContinentNameAndRegionName($continentName, $regionName);

        if (! $adminRegion instanceof GeoRegion) {
            throw new \RuntimeException(sprintf('GeoRegion "%s" in continent "%s" not found.', $regionName, $continentName));
        }

        $userRepo = $manager->getRepository(User::class);

        foreach ($admins as $data) {
            $existingUser = $userRepo->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                continue;
            }

            $user = new User();
            $user->setRole('ROLE_ADMIN');
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            $manager->persist($user);

            $admin = new Admin();
            if (method_exists($admin, 'setUser')) {
                $admin->setUser($user);
            }
            $admin->setRegion($adminRegion);
            $manager->persist($admin);

            $adminProfile = new AdminProfile();
            $adminProfile->setUser($user);
            $adminProfile->setNickname($data['nickname']);
            $manager->persist($adminProfile);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GeoContinentAndRegionFixtures::class,
        ];
    }
}
