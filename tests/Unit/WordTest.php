<?php

namespace Tests\Unit;

use App\Console\Commands\Words;
use Tests\TestCase;

class WordTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSetCaseTest()
    {
        $command = new Words();
        $word = $command->setCase('Abdul');
        $this->assertEquals('Abdul', $word);
    }
}
