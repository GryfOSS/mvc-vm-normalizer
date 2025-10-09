<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Normalizer;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\Persistence\Proxy;
use GryfOSS\Mvc\Attribute\DefaultViewModel;
use GryfOSS\Mvc\Model\NormalizableInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Default View Model Normalizer
 *
 * This normalizer automatically converts entities/models marked with the DefaultViewModel attribute
 * into their corresponding view models before normalization. It acts as an intermediate layer
 * between your data models and the final serialized output.
 */
class DefaultViewModelNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * Normalizes an object by converting it to its configured view model first.
     *
     * This method looks for the DefaultViewModel attribute on the object's class,
     * instantiates the configured view model with the original object, and then
     * delegates normalization to the next normalizer in the chain.
     *
     * @param mixed $object The object to normalize
     * @param string|null $format The format being normalized to
     * @param array $context Additional context for normalization
     * @return array|string|int|float|bool|\ArrayObject|null The normalized data
     * @throws ExceptionInterface If normalization fails
     * @throws \InvalidArgumentException If no DefaultViewModel attribute is found
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $class = $object instanceof Proxy ? ClassUtils::getClass($object) : $object::class;
        $reflector = new \ReflectionClass($class);
        $attributes = $reflector->getAttributes(DefaultViewModel::class);

        if (empty($attributes)) {
            throw new \InvalidArgumentException("No DefaultViewModel attribute found on class {$class}");
        }

        /** @var DefaultViewModel */
        $instance = $attributes[0]->newInstance();
        $viewModelClass = $instance->getViewModelClass();
        return $this->normalizer->normalize(new $viewModelClass($object), $format, $context);
    }

    /**
     * Determines if this normalizer can handle the given data.
     *
     * This normalizer supports objects that implement NormalizableInterface
     * and have the DefaultViewModel attribute configured.
     *
     * @param mixed $data The data to check for normalization support
     * @param string|null $format The format being normalized to
     * @param array $context Additional context for normalization
     * @return bool True if this normalizer can handle the data, false otherwise
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (!$data instanceof NormalizableInterface) {
            return false;
        }

        // It is a candidate, now we need to check if it has DefaultViewModel
        $class = $data instanceof Proxy ? ClassUtils::getClass($data) : $data::class;
        $reflector = new \ReflectionClass($class);
        $attributes = $reflector->getAttributes(DefaultViewModel::class);
        if (empty($attributes)) {
            return false;
        }

        return true;
    }

    /**
     * Gets the types supported by this normalizer.
     *
     * Returns an array indicating that this normalizer supports any class
     * implementing NormalizableInterface.
     *
     * @param string|null $format The format being normalized to
     * @return array<string, bool> Array mapping supported types to boolean true
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            NormalizableInterface::class => true
        ];
    }
}
