<?php namespace Ace\Dictionary;

use Ace\DictionaryInterface;

/**
* implements DictionaryInterface using a data grouped by word length
*/
class SortedDictionary implements DictionaryInterface
{
    /**
    * @var array of Word data
    */
    protected $dict = [];

    /**
    * @var string
    */
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function find($pattern, $length)
    {
        $this->load();    

        // assume that $pattern contains ^ and $ chars
        $words = $this->dict[$length];
        preg_match_all('#'.$pattern.'#m', $words, $matches);
        return $matches[0];
    }

    public function words($length)
    {
        $this->load();    

        if (isset($this->dict[$length])){
            $words = $this->dict[$length];
            return explode("\n", $words);
        } else {
            return [];
        }
    }

    public function longestWord()
    {
        $this->load();

        $lengths = array_keys($this->dict);
        if (!count($lengths)){
            return 0;
        }
        sort($lengths);
        return array_pop($lengths);
    }

    protected function load()
    {
        if (!count($this->dict)){
            $this->dict = require($this->file);
        }
    }
}
