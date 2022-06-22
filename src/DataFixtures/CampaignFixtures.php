<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Campaign;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public const CAMPAIGNS = [
        ['name' => 'Le meilleur langage back',
        'description' => 'Campagne de vote pour élire le meilleur langage de tous les temps',
        'has_college' => true,
        'company' => 'company_Wild',
        'created_at' => '2022-06-10',
        'status' => false
        ],
        ['name' => 'Wilder du mois',
        'description' => 'Campagne de vote pour élire le wilder du mois',
        'has_college' => false,
        'company' => 'company_Dephants',
        'created_at' => '2022-06-10',
        'status' => true
        ]
    ];


    public function load(ObjectManager $manager): void
    {
        foreach (self::CAMPAIGNS as $key => $campaignName) {
            $campaign = new Campaign();
            $campaign->setName($campaignName['name']);
            $campaign->setUuid('1234' . $key);
            $campaign->setDescription($campaignName['description']);
            $campaign->setHasCollege($campaignName['has_college']);
            $campaign->setCompany($this->getReference($campaignName['company']));
            $campaign->setCreatedAt(new DateTime($campaignName['created_at']));
            $campaign->setStatus($campaignName['status']);
            $this->addReference('campaign_' . $key, $campaign);
            $manager->persist($campaign);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
        CompanyFixtures::class,
        ];
    }
}
