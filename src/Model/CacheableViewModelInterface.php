<?php

namespace Praetorian\Mvc\Model;
use Symfony\Component\Serializer\Annotation\Ignore;

interface CacheableViewModelInterface
{
    public function getCacheKey(): string;
}