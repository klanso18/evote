<?php

namespace App\DataFixtures;

use App\Entity\Campaign;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

class CampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public const CAMPAIGNS = [
        ['company' => 'company_Nobatek',
        'name' => 'Conseil d\'administration',
        'status' => 1,
        'result' => 40],
        ['company' => 'company_Ceebios',
        'name' => 'Assemblée générale',
        'status' => 0,
        'result' => 50 ],
        ['company' => 'company_Cheops',
        'name' => 'Conseil d\'administration',
        'status' => 1,
        'result' => 30],
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach (self::CAMPAIGNS as $key => $campaignName) {
            $campaign = new Campaign();
            $campaign->setUuid('1234' . $key);
            $campaign->setCompany($this->getReference($campaignName['company']));
            $campaign->setName($campaignName['name']);
            $campaign->setStartedAt($faker->dateTimeBetween('-1 week', '+3 days'));
            $campaign->setStatus($faker->boolean('status'));
            $campaign->setResult($faker->randomNumber());
            $manager->persist($campaign);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [(CompanyFixtures::class)];
    }
}
