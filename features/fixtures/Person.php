<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Behat\Fixtures;

use GryfOSS\Mvc\Attribute\DefaultViewModel;
use GryfOSS\Mvc\Model\NormalizableInterface;

/**
 * Test entity representing a person with first and last name
 */
#[DefaultViewModel(viewModelClass: PersonViewModel::class)]
class Person implements NormalizableInterface
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private int $age
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}
