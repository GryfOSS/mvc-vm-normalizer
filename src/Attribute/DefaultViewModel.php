<?php

namespace Praetorian\Mvc\Attribute;

use InvalidArgumentException;
use Praetorian\Mvc\Model\ViewModelInterface;
use Attribute;

#[Attribute]
class DefaultViewModel
{
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
     * Get the value of viewModelClass
     */
    public function getViewModelClass() : string
    {
        return $this->viewModelClass;
    }
}