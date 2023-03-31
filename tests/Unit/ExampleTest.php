<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Http\Controllers\BlockControllerTest;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $test= new BlockControllerTest();
        $this->assertTrue($test->testBlock(1));
    }
}
