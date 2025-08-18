<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Unit;

use GryfOSS\Mvc\Attribute\DefaultViewModel;
use GryfOSS\Mvc\Model\NormalizableInterface;
use GryfOSS\Mvc\Model\ViewModelInterface;
use GryfOSS\Mvc\Normalizer\DefaultViewModelNormalizer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Comprehensive test for edge cases and complete coverage
 *
 * @covers \GryfOSS\Mvc\Normalizer\DefaultViewModelNormalizer
 * @covers \GryfOSS\Mvc\Attribute\DefaultViewModel
 */
class ComprehensiveCoverageTest extends TestCase
{
    public function testDefaultViewModelAttributeWithEdgeCases(): void
    {
        // Test with fully qualified class name
        $attribute = new DefaultViewModel(TestViewModelForCoverage::class);
        $this->assertSame(TestViewModelForCoverage::class, $attribute->getViewModelClass());

        // Test constructor validation paths are fully exercised
        $this->assertTrue(class_exists($attribute->getViewModelClass()));

        $reflection = new ReflectionClass($attribute->getViewModelClass());
        $interfaces = $reflection->getInterfaceNames();
        $this->assertContains(ViewModelInterface::class, $interfaces);
    }

    public function testNormalizerWithMissingAttribute(): void
    {
        $normalizer = new DefaultViewModelNormalizer();
        $mockNormalizer = $this->createMock(\Symfony\Component\Serializer\Normalizer\NormalizerInterface::class);
        $normalizer->setNormalizer($mockNormalizer);

        $entityWithoutAttribute = new class implements NormalizableInterface {
            public string $data = 'test';
        };

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No DefaultViewModel attribute found on class');

        $normalizer->normalize($entityWithoutAttribute);
    }

    public function testNormalizerWithEmptyAttributesArray(): void
    {
        $normalizer = new DefaultViewModelNormalizer();

        // This should trigger the empty attributes check in normalize method
        $objectWithoutAttribute = new class implements NormalizableInterface {};

        $this->expectException(\InvalidArgumentException::class);
        $normalizer->normalize($objectWithoutAttribute);
    }

    public function testGetSupportedTypesMethodCoverage(): void
    {
        $normalizer = new DefaultViewModelNormalizer();

        // Test with different format values to ensure complete coverage
        $jsonTypes = $normalizer->getSupportedTypes('json');
        $xmlTypes = $normalizer->getSupportedTypes('xml');
        $nullTypes = $normalizer->getSupportedTypes(null);

        $expectedTypes = [NormalizableInterface::class => true];

        $this->assertSame($expectedTypes, $jsonTypes);
        $this->assertSame($expectedTypes, $xmlTypes);
        $this->assertSame($expectedTypes, $nullTypes);
    }

    public function testSupportsNormalizationMethodCoverage(): void
    {
        $normalizer = new DefaultViewModelNormalizer();

        // Test all branches of supportsNormalization

        // 1. Non-NormalizableInterface object
        $stdObject = new \stdClass();
        $this->assertFalse($normalizer->supportsNormalization($stdObject));

        // 2. NormalizableInterface but no attribute
        $noAttributeObject = new class implements NormalizableInterface {};
        $this->assertFalse($normalizer->supportsNormalization($noAttributeObject));

        // 3. NormalizableInterface with attribute
        $withAttributeObject = new TestEntityForCoverage();
        $this->assertTrue($normalizer->supportsNormalization($withAttributeObject));

        // Test with different formats and contexts
        $this->assertTrue($normalizer->supportsNormalization($withAttributeObject, 'json', []));
        $this->assertTrue($normalizer->supportsNormalization($withAttributeObject, 'xml', ['groups' => ['test']]));
        $this->assertTrue($normalizer->supportsNormalization($withAttributeObject, null, ['custom' => 'context']));
    }

    public function testNormalizeMethodWithDifferentContexts(): void
    {
        $normalizer = new DefaultViewModelNormalizer();
        $mockNormalizer = $this->createMock(\Symfony\Component\Serializer\Normalizer\NormalizerInterface::class);
        $normalizer->setNormalizer($mockNormalizer);

        $testEntity = new TestEntityForCoverage();

        $mockNormalizer
            ->expects($this->exactly(3))
            ->method('normalize')
            ->willReturn(['test' => 'data']);

        // Test normalize with different parameter combinations
        $result1 = $normalizer->normalize($testEntity);
        $result2 = $normalizer->normalize($testEntity, 'json');
        $result3 = $normalizer->normalize($testEntity, 'xml', ['custom' => 'context']);

        $this->assertSame(['test' => 'data'], $result1);
        $this->assertSame(['test' => 'data'], $result2);
        $this->assertSame(['test' => 'data'], $result3);
    }

    public function testProxyHandlingBranches(): void
    {
        $normalizer = new DefaultViewModelNormalizer();
        $mockNormalizer = $this->createMock(\Symfony\Component\Serializer\Normalizer\NormalizerInterface::class);
        $normalizer->setNormalizer($mockNormalizer);

        // Test with regular object (non-proxy)
        $regularEntity = new TestEntityForCoverage();

        $mockNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->willReturn(['regular' => 'entity']);

        $result = $normalizer->normalize($regularEntity);
        $this->assertSame(['regular' => 'entity'], $result);

        // Test supportsNormalization with regular object
        $this->assertTrue($normalizer->supportsNormalization($regularEntity));
    }

    public function testAttributeConstructorValidationBranches(): void
    {
        // Test successful construction
        $validAttribute = new DefaultViewModel(TestViewModelForCoverage::class);
        $this->assertInstanceOf(DefaultViewModel::class, $validAttribute);

        // Test class existence validation
        try {
            new DefaultViewModel('CompletelyNonExistentClassName123');
            $this->fail('Expected InvalidArgumentException for non-existent class');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('$viewModelClass class provided does not exist.', $e->getMessage());
        }

        // Test ViewModelInterface implementation validation
        try {
            new DefaultViewModel(\stdClass::class);
            $this->fail('Expected InvalidArgumentException for class not implementing ViewModelInterface');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('ViewModel class must be an instance of ViewModelInterface.', $e->getMessage());
        }
    }
}

#[DefaultViewModel(viewModelClass: TestViewModelForCoverage::class)]
class TestEntityForCoverage implements NormalizableInterface
{
    public function __construct(
        public string $name = 'Test Entity Coverage',
        public int $value = 42
    ) {
    }
}

class TestViewModelForCoverage implements ViewModelInterface
{
    public function __construct(private TestEntityForCoverage $entity)
    {
    }

    public function getName(): string
    {
        return $this->entity->name;
    }

    public function getValue(): int
    {
        return $this->entity->value;
    }
}
