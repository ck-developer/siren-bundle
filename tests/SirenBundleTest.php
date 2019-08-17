<?php

namespace Siren\Bundle\Tests;

use Siren\Bundle\SirenBundle;
use PHPUnit\Framework\TestCase;

class SirenBundleTest extends TestCase
{
    public function testBundleName()
    {
        $bundle = new SirenBundle();

        $this->assertEquals('SirenBundle', $bundle->getName());
    }
}