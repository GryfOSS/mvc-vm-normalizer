<?php

namespace GryfOSS\Mvc\Model;

/**
 * Normalizable Interface
 *
 * Marker interface for entities or models that can be processed by the DefaultViewModelNormalizer.
 * Classes implementing this interface are candidates for automatic view model conversion
 * when they also have the DefaultViewModel attribute configured.
 *
 * This interface serves as a safety mechanism to ensure only intended classes
 * are processed by the view model normalization system.
 *
 * @package GryfOSS\Mvc\Model
 */
interface NormalizableInterface
{

}