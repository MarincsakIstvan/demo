<?php

namespace App\Service;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ICNDB
 * @package App\Service
 */
class ICNDBCrawler
{
    const URL_PATTERN = 'http://api.icndb.com/jokes/random/%d';
    const SOURCE = 'icndb';

    private $client;

    /**
     * ICNDB constructor.
     */
    public function __construct()
    {
        $this->client = new Client([]);
    }

    /**
     * @param $number
     * @return string
     * @throws \Exception
     */
    public function getJokes($number)
    {
        try {
            return $this->getAndParseJokes($number);
        } catch (\Exception $ex) {
            // TODO: Logging here
            throw new \Exception(
                sprintf('Cannot load random jokes', null, $ex)
            );
        }
    }

    /**
     * @param $number
     * @return array
     * @throws \Exception
     */
    private function getAndParseJokes($number)
    {
        /** @var [] $result */
        $result = [];

        /** @var ResponseInterface $response */
        $response = $this->client->get(
            sprintf(self::URL_PATTERN, $number)
        );

        $rawJokes = json_decode($response->getBody()->getContents(), true);

        if ($rawJokes === false) {
            throw new \Exception('Cannot parse jokes in class: ', self::class);
        }

        $this->validateResultSetFormat($rawJokes);

        foreach ($rawJokes['value'] as $joke) {
            if (!array_key_exists('joke', $joke)) {
                throw new \Exception(
                    sprintf('Invalid JSON response in class: %s', self::class)
                );
            }
            $result[] = [
                'message' => $joke['joke'],
                'source' => self::SOURCE
            ];
        }

        return array_reverse($result);
    }

    /**
     * @param $response
     * @return bool
     * @throws \Exception
     */
    private function validateResultSetFormat($response)
    {
        if (!array_key_exists('value', $response) || !is_array($response['value'])) {
            throw new \Exception(
                sprintf('Invalid JSON response in class: %s', self::class)
            );
        }

        return true;
    }
}
