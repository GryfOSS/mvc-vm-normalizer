<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Unit\Attribute;

use GryfOSS\Mvc\Attribute\DefaultViewModel;
use GryfOSS\Mvc\Model\ViewModelInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GryfOSS\Mvc\Attribute\DefaultViewModel
 */
class DefaultViewModelTest extends TestCase
{
    public function testConstructorWithValidViewModelClass(): void
    {
        $attribute = new DefaultViewModel(TestViewModel::class);

        $this->assertSame(TestViewModel::class, $attribute->getViewModelClass());
    }

    public function testConstructorWithNonExistentClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$viewModelClass class provided does not exist.');

        new DefaultViewModel('NonExistentClass');
    }

    public function testConstructorWithClassNotImplementingViewModelInterface(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ViewModel class must be an instance of ViewModelInterface.');

        new DefaultViewModel(\stdClass::class);
    }

    public function testGetViewModelClass(): void
    {
        $attribute = new DefaultViewModel(TestViewModel::class);

        $this->assertSame(TestViewModel::class, $attribute->getViewModelClass());
    }
}

/**
 * Test implementation of ViewModelInterface for testing purposes
 */
class TestViewModel implements ViewModelInterface
{
    public function __construct(private object $data)
    {
    }

    public function getData(): object
    {
        return $this->data;
    }
}
