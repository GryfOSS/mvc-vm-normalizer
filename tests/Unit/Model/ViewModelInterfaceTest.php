<?php

declare(strict_types=1);

namespace GryfOSS\Mvc\Tests\Unit\Model;

use GryfOSS\Mvc\Model\ViewModelInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for ViewModelInterface
 * @coversNothing
 */
class ViewModelInterfaceTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(ViewModelInterface::class));
    }

    public function testCanBeImplemented(): void
    {
        $implementation = new class implements ViewModelInterface {};

        $this->assertInstanceOf(ViewModelInterface::class, $implementation);
    }
}
