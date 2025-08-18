<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Unit\Model;

use GryfOSS\Mvc\Model\CacheableViewModelInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for CacheableViewModelInterface
 * @coversNothing
 */
class CacheableViewModelInterfaceTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(CacheableViewModelInterface::class));
    }

    public function testExtendsNormalizerInterface(): void
    {
        $reflection = new \ReflectionClass(CacheableViewModelInterface::class);
        $interfaces = $reflection->getInterfaceNames();

        $this->assertContains(NormalizerInterface::class, $interfaces);
    }

    public function testCanBeImplemented(): void
    {
        $implementation = new class implements CacheableViewModelInterface {
            public function getCacheKey(): string
            {
                return 'test-cache-key';
            }

            public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
            {
                return ['test' => 'data'];
            }

            public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
            {
                return true;
            }

            public function getSupportedTypes(?string $format): array
            {
                return ['*' => false];
            }
        };

        $this->assertInstanceOf(CacheableViewModelInterface::class, $implementation);
        $this->assertSame('test-cache-key', $implementation->getCacheKey());
    }

    public function testGetCacheKeyMethod(): void
    {
        $reflection = new \ReflectionClass(CacheableViewModelInterface::class);

        $this->assertTrue($reflection->hasMethod('getCacheKey'));

        $method = $reflection->getMethod('getCacheKey');
        $this->assertTrue($method->isPublic());
        $this->assertSame('string', (string) $method->getReturnType());
    }
}
