<?php

namespace Praetorian\Mvc\Model;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface CacheableViewModelInterface extends NormalizerInterface
{
    public function getCacheKey(): string;
}