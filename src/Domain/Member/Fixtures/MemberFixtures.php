<?php

namespace App\Domain\Member\Fixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Domain\User\Entity\User;
use App\Domain\Member\Entity\Member;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Geo\Repository\GeoRegionRepository;
use App\Domain\Member\Entity\MemberActivationCode;
use App\Domain\Member\Entity\MemberProfile;
use App\Domain\Geo\Fixtures\GeoContinentAndRegionFixtures;

/**
 * Fixture for members.
 */
class MemberFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly GeoRegionRepository $regionRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        $members = [
            [
                'email' => 'member@example.com',
                'password' => '12345678aZ!',
                'nickname' => 'member',
            ],
        ];

        $continentName = 'Europe';
        $regionName = 'Western';
        /** @var \App\Repository\GeoRegionRepository $region */
        $region = $manager->getRepository(GeoRegion::class);
        $memberRegion = $region->regionByContinentNameAndRegionName($continentName, $regionName);

        if (! $memberRegion instanceof GeoRegion) {
            throw new \RuntimeException(sprintf('GeoRegion "%s" in continent "%s" not found.', $regionName, $continentName));
        }

        $userRepo = $manager->getRepository(User::class);

        foreach ($members as $data) {
            $existingUser = $userRepo->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                continue;
            }

            $user = new User();
            $user->setRole('ROLE_MEMBER');
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            $manager->persist($user);

            $member = new Member();
            if (method_exists($member, 'setUser')) {
                $member->setUser($user);
            }
            $member->setRegion($memberRegion);
            $manager->persist($member);

            $activationCode = new MemberActivationCode();
            $activationCode->setUser($user);
            $activationCode->setIsActive(true);
            $manager->persist($activationCode);

            $memberProfile = new MemberProfile();
            $memberProfile->setUser($user);
            $memberProfile->setNickname($data['nickname']);
            $manager->persist($memberProfile);
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
