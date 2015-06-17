<?php namespace Ace\Dictionary;

/**
* implements DictionaryInterface using a data grouped by word length
*/
class SortedDictionary implements DictionaryInterface
{
    /**
    * @var array of Word data
    */
    private $dict = [];

    /**
    * @var string
    */
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function find($pattern, $length)
    {
        $dict = $this->getDict();

        // assume that $pattern does not contain ^ and $ chars
        $words = $dict[$length];
        preg_match_all('#^'.$pattern.'$#m', $words, $matches);
        return $matches[0];
    }

    public function words($length)
    {
        $dict = $this->getDict();

        if (isset($dict[$length])){
            $words = $dict[$length];
            return explode("\n", $words);
        } else {
            return [];
        }
    }

    public function longestWord()
    {
        $dict = $this->getDict();

        $lengths = array_keys($dict);
        if (!count($lengths)){
            return 0;
        }
        sort($lengths);
        return array_pop($lengths);
    }

    private function getDict()
    {
        if (!count($this->dict)){
            $this->dict = require($this->file);
        }

        return $this->dict;
    }
}
