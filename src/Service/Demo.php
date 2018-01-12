<?php


namespace App\Service;
/**
 * Class Demo
 * @package App\Service
 */
class Demo
{
    const METHOD_MOD = 'mod';
    const METHOD_FIB = 'fib';
    const HANDLE_LENGTH = 20;

    /**
     * @var array
     */
    private $fibNumbers = [2, 3, 5, 8, 13, 21, 34, 55];

    /**
     * @var TwitterCrawler
     */
    private $twitterService;

    /**
     * @var ICNDBCrawler
     */
    private $icndbService;

    /**
     * Demo constructor.
     * @param TwitterCrawler $twitterService
     * @param ICNDBCrawler   $ICNDBService
     */
    public function __construct(TwitterCrawler $twitterService, ICNDBCrawler $ICNDBService)
    {
        $this->twitterService = $twitterService;
        $this->icndbService = $ICNDBService;
    }

    /**
     * @param $handle1
     * @param $handle2
     * @param $mod
     * @return array
     * @throws \Exception
     */
    public function getResult($handle1, $handle2, $mod)
    {
        $this->validateHandles($handle1, $handle2);

        $result1 = $this->twitterService->getTweets($handle1);
        $result2 = $this->twitterService->getTweets($handle2);

        $this->validateResultLength($result1, self::HANDLE_LENGTH);
        $this->validateResultLength($result2, self::HANDLE_LENGTH);

        switch ($mod) {
            case self::METHOD_FIB:
                $jokes = $this->icndbService->getJokes(10);
                $this->validateResultLength($jokes, 10);

                return $this->sortByFib($result1, $result2, $jokes);
                break;
            case self::METHOD_MOD:
                $jokes = $this->icndbService->getJokes(20);
                $this->validateResultLength($jokes, 20);

                return $this->sortByMod($result1, $result2, $jokes);
                break;
            default:
                throw new \Exception(
                    sprintf('Invalid method "%s" in class: %s', $mod, self::class)
                );
        }
    }

    /**
     * @param array $res1
     * @param array $res2
     * @param array $res3
     * @return array
     */
    private function sortByMod(array $res1, array $res2, array $res3)
    {
        $return = [];

        for ($i = 0; $i < 20; $i++) {
            for ($j = 1; $j < 4; $j++) {
                $arrName = 'res' . $j;
                $return[] = $$arrName[$i];
            }
        }

        return $return;
    }

    /**
     * @param array $res1
     * @param array $res2
     * @param array $jokes
     * @return array
     */
    private function sortByFib(array $res1, array $res2, array $jokes)
    {
        $return = [];
        $jokeIndex = 0;
        $totalNumCounter = 0;

        for ($i = 0; $i < 20; $i++) {
            for ($j = 1; $j < 3; $j++) {
                if (in_array($totalNumCounter, $this->fibNumbers)) {
                    $return[] = $jokes[$jokeIndex];
                    $jokeIndex++;
                }
                $arrName = 'res' . $j;
                $return[] = $$arrName[$i];
                $totalNumCounter++;
            }
        }

        return $return;
    }

    /**
     * @param $handle1
     * @param $handle2
     * @return bool
     * @throws \Exception
     */
    private function validateHandles($handle1, $handle2)
    {
        if ($handle1 == $handle2) {
            throw new \Exception(
                sprintf('Same values: "%s" in class: %s', $handle1, self::class)
            );
        };

        return true;
    }

    /**
     * @param array $handleResult
     * @param int   $length
     * @return bool
     * @throws \Exception
     */
    private function validateResultLength(array $handleResult, $length = 0)
    {
        $len = count($handleResult);

        if ($len !== $length) {
            throw new \Exception(
                sprintf('Invlaid result length[%d] in class: %s', $len, self::class)
            );
        }

        return true;
    }
}