<?php

namespace Tests\Unit;

use App\Console\Commands\Words;
use App\Words\WordCloud;
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
        $word_info = $command->setCaseInfo('sunday\'s');
        $this->assertEquals('Sunday\'s', $word_info['word']);
        $word_info = $command->setCaseInfo('dr');
        $this->assertEquals('Dr', $word_info['word']);
        $word_info = $command->setCaseInfo('tristesse');
        $this->assertEquals('tristesse', $word_info['word']);
        $word_info = $command->setCaseInfo('mein');
        $this->assertEquals('mein', $word_info['word']);
        $word_info = $command->setCaseInfo('spiacente');
        $this->assertEquals('spiacente', $word_info['word']);
        $word_info = $command->setCaseInfo('cuatro');
        $this->assertEquals('cuatro', $word_info['word']);
        $word_info = $command->setCaseInfo('xmas');
        $this->assertEquals('Xmas', $word_info['word']);
        $word_info = $command->setCaseInfo('brooklyn');
        $this->assertEquals('Brooklyn', $word_info['word']);
        $word_info = $command->setCaseInfo('alabama');
        $this->assertEquals('Alabama', $word_info['word']);
        $word_info = $command->setCaseInfo('junes');
        $this->assertEquals('Junes', $word_info['word']);
        $word_info = $command->setCaseInfo('tiananmen');
        $this->assertEquals('Tiananmen', $word_info['word']);
        $word_info = $command->setCaseInfo('baltimore');
        $this->assertEquals('Baltimore', $word_info['word']);
        $word_info = $command->setCaseInfo('halley');
        $this->assertEquals('Halley', $word_info['word']);
        $word_info = $command->setCaseInfo('i');
        $this->assertEquals('I', $word_info['word']);
        $word_info = $command->setCaseInfo('abc');
        $this->assertEquals('ABC', $word_info['word']);
        $word_info = $command->setCaseInfo('morcheeba');
        $this->assertEquals('Morcheeba', $word_info['word']);
        $word_info = $command->setCaseInfo('pash');
        $this->assertEquals('made_up', $word_info['type']);
        $word_info = $command->setCaseInfo('ASKS');
        $this->assertEquals('asks', $word_info['word']);
        $word_info = $command->setCaseInfo('kaerenai');
        $this->assertEquals('Japanese', $word_info['type']);
        $word_info = $command->setCaseInfo('vem');
        $this->assertEquals('Portugese', $word_info['type']);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testWordCloudTest()
    {
        $wordCloud = new WordCloud();
        $word_info = $wordCloud->setWord('Ireland');
        $this->assertEquals('Ireland', $word_info['word']);
        $this->assertEquals('country', $word_info['category']);
    }

    /**
     * String only contains dashes
     *
     * @return void
     */
    public function testDashesTest()
    {
        $pattern = '/^[-]+$/';
        $this->assertEquals(true, preg_match($pattern, '-'));
        $this->assertEquals(true, preg_match($pattern, '--'));
        $this->assertEquals(true, preg_match($pattern, '--'));
        $this->assertEquals(false, preg_match($pattern, 'a-b'));
        $this->assertEquals(true, ! preg_match($pattern, 'a-b'));
    }

    /**
     * Test word processing utility.
     *
     * @return void
     */
    public function testProcessWordTest()
    {
        $wordCloud = new WordCloud();
        $wordCloud->processWord('U.S.A.​');
        $this->assertEquals('USA', array_key_first($wordCloud->get_words()));
    }
}
