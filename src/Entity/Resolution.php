<?php

namespace App\Entity;

use App\Entity\Campaign;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResolutionRepository;

#[ORM\Entity(repositoryClass: ResolutionRepository::class)]
class Resolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $description;

    #[ORM\Column(type: 'string', length: 45)]
    private string $adoptionRule;

    #[ORM\ManyToOne(targetEntity: Campaign::class, inversedBy: 'resolutions')]
    private Campaign $campaign;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $uuid;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdoptionRule(): ?string
    {
        return $this->adoptionRule;
    }

    public function setAdoptionRule(string $adoptionRule): self
    {
        $this->adoptionRule = $adoptionRule;

        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
