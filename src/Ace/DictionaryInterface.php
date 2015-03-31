<?php namespace Ace;

/**
* Dictionary interface provides access to query a dictionary using a pattern
*/
interface DictionaryInterface
{
    /**
    * @return array of words (strings) of length $length that match pattern
    */
    public function find($pattern, $length);

    /**
    * @return array or words of length $length
    */
    public function words($length);

    /**
    * @return integer the length of the longest word
    */
    public function longestWord();
}
