<?php

require(__DIR__.'/../src/vendor/autoload.php');

use Ace\Dictionary\SortedDictionary;

/**
* @group integration
*/
class SortedDictionaryTest extends PHPUnit_Framework_TestCase
{
    public function getFixtures()
    {
        return [
            ['^.a.$', ['bat', 'mat']],
            ['^.ra..$', ['iraqi', 'irate']],
            ['^sta....$', ['station', 'staunch', 'starved']],
        ];
    }

    /**
    * @dataProvider getFixtures
    */
    public function testFindReturnsAllMatchingWords($pattern, $expected)
    {
        $dictionary = new SortedDictionary(__DIR__.'/fixtures/dict-2');
        $result = $dictionary->find($pattern, strlen($expected[0]));
        
        $this->assertTrue(is_array($result));
        foreach ($expected as $exp) {
            $this->assertTrue(in_array($exp, $result));
        }
    }

    public function testWordsReturnsEmptyWhenNoWordsOfLengthExist()
    {
        $dictionary = new SortedDictionary(__DIR__.'/fixtures/dict-2');
        $result = $dictionary->words(9);
        $this->assertTrue(is_array($result));
        $this->assertSame(0, count($result));
    }

    public function testWordsReturnsArrayOfWords()
    {
        $dictionary = new SortedDictionary(__DIR__.'/fixtures/dict-2');
        $result = $dictionary->words(3);
        $this->assertTrue(is_array($result));
        $this->assertSame(4, count($result));
    }

    public function testLongestWordReturnsZeroWhenNoWordsSet()
    {
        $dictionary = new SortedDictionary(__DIR__.'/fixtures/empty-dict');
        $result = $dictionary->longestWord();
        $this->assertSame(0, $result);
    }

    public function testLongestWord()
    {
        $dictionary = new SortedDictionary(__DIR__.'/fixtures/dict-2');
        $result = $dictionary->longestWord();
        $this->assertSame(7, $result);
    }
}
