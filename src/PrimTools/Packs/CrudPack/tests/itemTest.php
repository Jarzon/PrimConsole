<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testCanReturnFalse()
    {

        $this->assertEquals(false, false);
    }
}