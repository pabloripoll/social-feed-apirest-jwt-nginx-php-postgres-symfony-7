<?php

namespace App\Domain\Geo\Fixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Geo\Entity\GeoContinent;

/**
 * Fixture for geo_continents and geo_regions tables.
 */
class GeoContinentAndRegionFixtures extends Fixture
{
    /**
     * Using a Model by the United Nations (UN Geoscheme)
     * The United Nations geoscheme divides continents into further subgroups:
     */
    protected function data(): array
    {
        return [
            'Europe' => [
                'Eastern', 'Northern', 'Southern', 'Western',
            ],
            'Africa' => [
                'Eastern', 'Middle', 'Northern', 'Southern', 'Western',
            ],
            'Americas' => [
                'Caribbean', 'Central', 'Northern', 'South',
            ],
            'Asia' => [
                'Central', 'Eastern', 'South-Eastern', 'Southern', 'Western',
            ],
            'Oceania' => [
                'Australia and New Zealand', 'Melanesia', 'Micronesia', 'Polynesia',
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $continentRepo = $manager->getRepository(GeoContinent::class);
        $regionRepo = $manager->getRepository(GeoRegion::class);

        foreach ($this->data() as $continentName => $regions) {
            // find or create continent
            $continent = $continentRepo->findOneBy(['name' => $continentName]);
            if (! $continent instanceof GeoContinent) {
                $continent = new GeoContinent();
                $continent->setName($continentName);
                $manager->persist($continent);
            }

            // add a stable reference for other fixtures
            $this->addReference('continent.' . strtolower(str_replace(' ', '_', $continentName)), $continent);

            // create regions for the continent (idempotent)
            foreach ($regions as $regionName) {
                $regionExists = $regionRepo->findOneBy([
                    'name' => $regionName,
                    'continent' => $continent,
                ]);

                if ($regionExists instanceof GeoRegion) {
                    // ensure there is a reference for existing rows too
                    $this->addReference($this->regionRefKey($continentName, $regionName), $regionExists);
                    continue;
                }

                $region = new GeoRegion();
                $region->setContinent($continent);
                $region->setName($regionName);
                $manager->persist($region);

                // add reference so other fixtures can use getReference()
                $this->addReference($this->regionRefKey($continentName, $regionName), $region);
            }
        }

        // single flush for performance and atomicity
        $manager->flush();
    }

    private function regionRefKey(string $continentName, string $regionName): string
    {
        return sprintf(
            'region.%s.%s',
            strtolower(str_replace(' ', '_', $continentName)),
            strtolower(str_replace(' ', '_', $regionName))
        );
    }
}
