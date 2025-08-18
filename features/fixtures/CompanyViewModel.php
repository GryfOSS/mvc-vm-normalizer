<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Behat\Fixtures;

use GryfOSS\Mvc\Model\ViewModelInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * View model for Company that shows business info and owner details
 */
class CompanyViewModel implements ViewModelInterface
{
    public function __construct(private Company $company)
    {
    }

    #[SerializedName('companyName')]
    public function getName(): string
    {
        return $this->company->getName();
    }

    #[SerializedName('sector')]
    public function getIndustry(): string
    {
        return $this->company->getIndustry();
    }

    #[SerializedName('ownerInfo')]
    public function getOwner(): Person
    {
        return $this->company->getOwner();
    }

    public function getYearsInBusiness(): int
    {
        return date('Y') - $this->company->getFoundedYear();
    }
}
