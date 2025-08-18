<?php

namespace GryfOSS\Mvc\Model;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Cacheable View Model Interface
 *
 * Extended interface for view models that support caching mechanisms.
 * View models implementing this interface can provide a cache key
 * that can be used by caching layers to store and retrieve normalized data.
 *
 * This interface extends both ViewModelInterface (implicitly through usage)
 * and NormalizerInterface to provide full normalization capabilities
 * with caching support.
 *
 * @package GryfOSS\Mvc\Model
 */
interface CacheableViewModelInterface extends NormalizerInterface
{
    /**
     * Gets the cache key for this view model instance.
     *
     * The cache key should be unique and deterministic based on the
     * underlying data to ensure proper cache invalidation and retrieval.
     *
     * @return string A unique cache key for this view model instance
     */
    public function getCacheKey(): string;
}