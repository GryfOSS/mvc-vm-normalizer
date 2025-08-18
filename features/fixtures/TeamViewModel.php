<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Behat\Fixtures;

use GryfOSS\Mvc\Model\ViewModelInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * View model for Team that shows team info and member collection
 */
class TeamViewModel implements ViewModelInterface
{
    public function __construct(private Team $team)
    {
    }

    #[SerializedName('teamName')]
    public function getName(): string
    {
        return $this->team->getName();
    }

    #[SerializedName('dept')]
    public function getDepartment(): string
    {
        return $this->team->getDepartment();
    }

    #[SerializedName('teamMembers')]
    public function getMembers(): array
    {
        return $this->team->getMembers();
    }

    #[SerializedName('lead')]
    public function getTeamLead(): ?Person
    {
        return $this->team->getTeamLead();
    }

    #[SerializedName('size')]
    public function getTeamSize(): int
    {
        return $this->team->getMemberCount();
    }

    public function getAverageAge(): float
    {
        $members = $this->team->getMembers();
        if (empty($members)) {
            return 0.0;
        }

        $totalAge = array_sum(array_map(fn($member) => $member->getAge(), $members));
        return round($totalAge / count($members), 1);
    }
}
