<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Unit\Normalizer;

use Doctrine\Common\Proxy\Proxy;
use GryfOSS\Mvc\Attribute\DefaultViewModel;
use GryfOSS\Mvc\Model\NormalizableInterface;
use GryfOSS\Mvc\Model\ViewModelInterface;
use GryfOSS\Mvc\Normalizer\DefaultViewModelNormalizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @covers \GryfOSS\Mvc\Normalizer\DefaultViewModelNormalizer
 * @uses \GryfOSS\Mvc\Attribute\DefaultViewModel
 */
class DefaultViewModelNormalizerTest extends TestCase
{
    private DefaultViewModelNormalizer $normalizer;
    private NormalizerInterface $mockNormalizer;

    protected function setUp(): void
    {
        $this->normalizer = new DefaultViewModelNormalizer();
        $this->mockNormalizer = $this->createMock(NormalizerInterface::class);
        $this->normalizer->setNormalizer($this->mockNormalizer);
    }

    public function testNormalizeWithValidObject(): void
    {
        $testEntity = new TestEntity();
        $expectedResult = ['normalized' => 'data'];

        $this->mockNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->with($this->isInstanceOf(TestEntityViewModel::class))
            ->willReturn($expectedResult);

        $result = $this->normalizer->normalize($testEntity);

        $this->assertSame($expectedResult, $result);
    }

    public function testNormalizeWithProxyObject(): void
    {
        $testProxy = new TestEntityProxy();
        $expectedResult = ['normalized' => 'proxy_data'];

        $this->mockNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->with($this->isInstanceOf(TestEntityViewModel::class))
            ->willReturn($expectedResult);

        $result = $this->normalizer->normalize($testProxy);

        $this->assertSame($expectedResult, $result);
    }

    public function testNormalizeWithObjectWithoutAttribute(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No DefaultViewModel attribute found on class');

        $objectWithoutAttribute = new class implements NormalizableInterface {};
        $this->normalizer->normalize($objectWithoutAttribute);
    }

    public function testSupportsNormalizationWithValidObject(): void
    {
        $testEntity = new TestEntity();

        $this->assertTrue($this->normalizer->supportsNormalization($testEntity));
    }

    public function testSupportsNormalizationWithNonNormalizableObject(): void
    {
        $nonNormalizableObject = new \stdClass();

        $this->assertFalse($this->normalizer->supportsNormalization($nonNormalizableObject));
    }

    public function testSupportsNormalizationWithObjectWithoutAttribute(): void
    {
        $objectWithoutAttribute = new class implements NormalizableInterface {};

        $this->assertFalse($this->normalizer->supportsNormalization($objectWithoutAttribute));
    }

    public function testSupportsNormalizationWithProxy(): void
    {
        $testProxy = new TestEntityProxy();

        $this->assertTrue($this->normalizer->supportsNormalization($testProxy));
    }

    public function testGetSupportedTypes(): void
    {
        $result = $this->normalizer->getSupportedTypes('json');

        $expected = [
            NormalizableInterface::class => true
        ];

        $this->assertSame($expected, $result);
    }

    public function testGetSupportedTypesWithNullFormat(): void
    {
        $result = $this->normalizer->getSupportedTypes(null);

        $expected = [
            NormalizableInterface::class => true
        ];

        $this->assertSame($expected, $result);
    }

    public function testNormalizeWithDifferentFormats(): void
    {
        $testEntity = new TestEntity();
        $expectedResult = ['normalized' => 'data'];

        $this->mockNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->with(
                $this->isInstanceOf(TestEntityViewModel::class),
                'xml',
                ['custom' => 'context']
            )
            ->willReturn($expectedResult);

        $result = $this->normalizer->normalize($testEntity, 'xml', ['custom' => 'context']);

        $this->assertSame($expectedResult, $result);
    }

    public function testSupportsNormalizationWithDifferentFormats(): void
    {
        $testEntity = new TestEntity();

        $this->assertTrue($this->normalizer->supportsNormalization($testEntity, 'json'));
        $this->assertTrue($this->normalizer->supportsNormalization($testEntity, 'xml'));
        $this->assertTrue($this->normalizer->supportsNormalization($testEntity, null));
    }
}

/**
 * Test entity for testing purposes
 */
#[DefaultViewModel(viewModelClass: TestEntityViewModel::class)]
class TestEntity implements NormalizableInterface
{
    public function __construct(
        public string $name = 'Test Entity',
        public int $id = 1
    ) {
    }
}

/**
 * Test view model for testing purposes
 */
class TestEntityViewModel implements ViewModelInterface
{
    public function __construct(private TestEntity $entity)
    {
    }

    public function getName(): string
    {
        return $this->entity->name;
    }

    public function getId(): int
    {
        return $this->entity->id;
    }
}

/**
 * Test proxy class that simulates Doctrine proxy behavior
 */
#[DefaultViewModel(viewModelClass: TestEntityViewModel::class)]
class TestEntityProxy extends TestEntity implements Proxy
{
    public function __load(): void
    {
        // Simulate proxy loading
    }

    public function __isInitialized(): bool
    {
        return true;
    }

    public function __setInitialized($initialized): void
    {
        // Simulate setting initialization state
    }

    public function __setInitializer(\Closure $initializer = null): void
    {
        // Simulate setting initializer
    }

    public function __getInitializer(): ?\Closure
    {
        return null;
    }

    public function __setCloner(\Closure $cloner = null): void
    {
        // Simulate setting cloner
    }

    public function __getCloner(): ?\Closure
    {
        return null;
    }

    public function __getLazyProperties(): array
    {
        return [];
    }
}
