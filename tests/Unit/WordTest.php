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
        $word_info = $command->setCaseInfo('Abdul');
        $this->assertEquals('Abdul', $word_info['word']);
        $word_info = $command->setCaseInfo('abc');
        $this->assertEquals('ABC', $word_info['word']);
    }
}
