<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Behat\Fixtures;

use GryfOSS\Mvc\Model\ViewModelInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * View model for Person that combines firstName and lastName into a single name field
 */
class PersonViewModel implements ViewModelInterface
{
    public function __construct(private Person $person)
    {
    }

    #[SerializedName('n')]
    public function getName(): string
    {
        return $this->person->getFirstName() . ' ' . $this->person->getLastName();
    }

    #[SerializedName('a')]
    public function getAge(): int
    {
        return $this->person->getAge();
    }
}
