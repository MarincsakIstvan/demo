<?php

namespace App\Service;

use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Class TwitterCrawler
 * @package App\Service
 */
class TwitterCrawler
{
    /**
     * User timeline relative URL
     */
    const TIMELINE_URL = 'statuses/user_timeline';
    const COUNT = 20;

    /**
     * @var TwitterOAuth
     */
    private $twitterConnection;

    /**
     * TwitterCrawler constructor.
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     */
    public function __construct($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret)
    {
        $this->twitterConnection = new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
    }

    /**
     * @param $userScreenName
     * @return array|object
     * @throws \Exception
     */
    public function getTweets($userScreenName)
    {
        try {
            $data = $this->twitterConnection->get(
                self::TIMELINE_URL, [
                    'screen_name' => $userScreenName,
                    'count'       => self::COUNT
                ]
            );

            return $this->parseTweets($data, $userScreenName);
        } catch (\Exception $ex) {
            // TODO: Logging here
            throw new \Exception(
                sprintf('Twitter crawler exception with username: %s', $userScreenName, null, $ex)
            );
        }
    }

    /**
     * @param array   $data
     * @param  string $userScreenName
     * @return array
     */
    private function parseTweets(array $data, $userScreenName)
    {
        $parsedResult = [];

        foreach ($data as $item) {
            $parsedResult[] = [
                'message'    => $item->text,
                'source'     => sprintf('twitter/%s', $userScreenName),
                'created_at' => $item->created_at
            ];
        }

        return array_reverse($parsedResult);
    }
}
