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

        foreach (self::CAMPAIGNS as $campaignName) {
            $campaign = new Campaign();
            $uuid = Uuid::v4();
            $campaign->setUuid($uuid->toRfc4122());
            $campaign->setCompany($this->getReference($campaignName['company']));
            $campaign->setName($campaignName['name']);
            $campaign->setCreatedAt($faker->dateTimeThisYear());
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