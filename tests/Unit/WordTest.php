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
        $word_info = $command->setCaseInfo('DiMaggio');
        $this->assertEquals('DiMaggio', $word_info['word']);
        $word_info = $command->setCaseInfo('DMC');
        $this->assertEquals('DMC', $word_info['word']);
        $word_info = $command->setCaseInfo('jcrew');
        $this->assertEquals('JCrew', $word_info['word']);
        $word_info = $command->setCaseInfo('norwegian');
        $this->assertEquals('Norwegian', $word_info['word']);
        /*
        $word_info = $command->setCaseInfo('abc');
        $this->assertEquals('ABC', $word_info['word']);
        $word_info = $command->setCaseInfo('alabama');
        $this->assertEquals('Alabama', $word_info['word']);
        $word_info = $command->setCaseInfo('amtracks');
        $this->assertEquals('Amtracks', $word_info['word']);

        $word_info = $command->setCaseInfo('dr');
        $this->assertEquals('Dr', $word_info['word']);
        $word_info = $command->setCaseInfo('easter');
        $this->assertEquals('Easter', $word_info['word']);
        $word_info = $command->setCaseInfo('frater');
        $this->assertEquals('Frater', $word_info['word']);
        $word_info = $command->setCaseInfo('Mein');
        $this->assertEquals('mein', $word_info['word']);
        */
    }
}
