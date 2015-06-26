<?php

namespace Imanee\Tests;

use Imanee\PhpExtensionAvailabilityChecker;
use PHPUnit_Framework_TestCase;

class PhpExtensionAvailabilityCheckerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PhpExtensionAvailabilityChecker;
     */
    private $PhpExtensionAvailabilityChecker;

    public function setUp()
    {
        $this->PhpExtensionAvailabilityChecker = new PhpExtensionAvailabilityChecker();
    }

    public function testShouldConfirmDefaultExtensionIsLoaded()
    {
        $this->assertTrue($this->PhpExtensionAvailabilityChecker->isLoaded('Core'));
    }

    public function testShouldConfirmUnknownExtensionIsNotLoaded()
    {
        $this->assertFalse($this->PhpExtensionAvailabilityChecker->isLoaded('foo-bar'));
    }
}
