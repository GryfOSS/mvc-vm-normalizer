<?php

declare(strict_types=1);

namespace Praetorian\Mvc\Normalizer;

use Doctrine\Common\Proxy\Proxy;
use Doctrine\Common\Util\ClassUtils;
use Praetorian\BettingBundle\Model\ViewModel\MarketViewModel;
use Praetorian\Mvc\Attribute\DefaultViewModel;
use Praetorian\Mvc\Model\NormalizableInterface;
use Praetorian\Sportsbook\Orm\Entity\Market;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultViewModelNormalizer implements NormalizerInterface
{
    /**
     * @param  ObjectNormalizer  $normalizer
     */
    public function __construct(protected readonly ObjectNormalizer $normalizer)
    {
    }

    /**
     * @param $object
     * @param  string|null  $format
     * @param  array  $context
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): mixed
    {
        $class = $object instanceof Proxy ? ClassUtils::getClass($object) : $object::class;
        $reflector = new \ReflectionClass($class);
        $attributes = $reflector->getAttributes(DefaultViewModel::class);

        /** @var DefaultViewModel */
        $instance = $attributes[0]->newInstance();
        $viewModelClass = $instance->getViewModelClass();

        return $this->normalizer->normalize(new $viewModelClass($object), $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (!$data instanceof NormalizableInterface) {
            return false;
        }

        //it is a candidate, now we need to check if it has DefaultViewModel
        $class = $data instanceof Proxy ? ClassUtils::getClass($data) : $data::class;
        $reflector = new \ReflectionClass($class);
        $attributes = $reflector->getAttributes(DefaultViewModel::class);
        if (empty($attributes)) {
            return false;
        }

        return true;
    }
}