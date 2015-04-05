<?php

use Silex\WebTestCase;

/**
 * @author timrodger
 * Date: 18/03/15
 */
class AppTest extends WebTestCase
{
    private $client;

    public function createApplication()
    {
        return require __DIR__.'/../src/app.php';
    }

    private function givenAClient()
    {
        $this->client = $this->createClient();
    }

    public function getQueries()
    {
        return [
            ['d..', '3', ['dog','dye', 'den', 'din']],
            ['sta....', '7', ['station','staunch', 'staying']]
        ];
    }

    /**
     * @dataProvider getQueries
     */
    public function testGetWordsMatchingPatternWithLength($pattern, $length, $expected)
    {
        $this->givenAClient();
        $this->client->request('GET', sprintf('/words?pattern=%s&length=%s', $pattern, $length));
        $this->thenTheResponseIsSuccess();
        $this->thenTheResponseContentsContain($expected);
    }

    /**
     * the dictionary was correctly queried and the results are correct, nothing matches the pattern
     * so respond with a 200 and an empty json array
     */
    public function testGetWordsMatchingPatternRespondsSuccessWhenNoneAreFound()
    {
        $this->givenAClient();
        $this->client->request('GET', '/words?pattern=xxxxxxx&length=4');
        $this->thenTheResponseIsSuccess();
        $actual = json_decode($this->client->getResponse()->getContent());
        $this->assertSame([], $actual);
    }

    public function testRequestWithoutLengthParamFails()
    {
        $this->givenAClient();
        $this->client->request('GET', '/words?pattern=xxxxxxx');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestWithNonIntegerLengthParamFails()
    {
        $this->givenAClient();
        $this->client->request('GET', '/words?pattern=xxxxxxx&length=x');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestWithoutPatternParamFails()
    {
        $this->givenAClient();
        $this->client->request('GET', '/words?length=5');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestWithEmptyPatternParamFails()
    {
        $this->givenAClient();
        $this->client->request('GET', '/words?pattern=&length=5');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    private function thenTheResponseIsSuccess()
    {
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    protected function thenTheResponseContentsContain(array $expected)
    {
        $actual = json_decode($this->client->getResponse()->getContent());
        $this->assertTrue(is_array($actual));

        foreach($expected as $word){
            $this->assertTrue(in_array($word, $actual), "Expected '$word'");
        }
    }
}