<?php

namespace GryfOSS\Mvc\Attribute;

use InvalidArgumentException;
use GryfOSS\Mvc\Model\ViewModelInterface;
use Attribute;

/**
 * Default View Model Attribute
 *
 * This attribute is used to specify which view model class should be used
 * when normalizing an entity or model. When applied to a class that implements
 * NormalizableInterface, the DefaultViewModelNormalizer will automatically
 * convert instances to the specified view model before normalization.
 *
 * Example usage:
 * ```php
 * #[DefaultViewModel(viewModelClass: UserViewModel::class)]
 * class User implements NormalizableInterface
 * {
 *     // ... class implementation
 * }
 * ```
 *
 * @package GryfOSS\Mvc\Attribute
 */
#[Attribute]
class DefaultViewModel
{
    /**
     * Constructor for DefaultViewModel attribute.
     *
     * Validates that the provided view model class exists and implements ViewModelInterface.
     *
     * @param string $viewModelClass The fully qualified class name of the view model to use
     * @throws InvalidArgumentException If the class doesn't exist or doesn't implement ViewModelInterface
     */
    public function __construct(protected readonly string $viewModelClass)
    {
        if (!class_exists($viewModelClass)) {
            throw new InvalidArgumentException('$viewModelClass class provided does not exist.');
        }

        $interfaces = class_implements($viewModelClass);

        if (!isset($interfaces[ViewModelInterface::class])) {
            throw new InvalidArgumentException("ViewModel class must be an instance of ViewModelInterface.");
        }
    }

    /**
     * Gets the view model class name configured for this attribute.
     *
     * @return string The fully qualified class name of the view model
     */
    public function getViewModelClass() : string
    {
        return $this->viewModelClass;
    }
}