<?php
/**
 * Created by PhpStorm.
 * User: scherk01
 * Date: 15.07.2015
 * Time: 16:54
 */

namespace GFB\SocialClientBundle\Service;

use Guzzle\Http\Client as GuzzleClient;

abstract class AbstractRestClient
{
    /** @var GuzzleClient */
    protected $client;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->client = new GuzzleClient($this->getApiUrl());
    }

    /**
     * @param string $method
     * @param array $context
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function prepareRequest($method, $context = array())
    {
        $request = $this->client->get($method);
        $query = $request->getQuery();
        foreach ($context as $key => $value) {
            $query->set($key, $value);
        }
        return $request;
    }

    /**
     * @param string $data
     * @return array
     */
    protected function prepareResponse($data)
    {
        return json_decode($data, true);
    }

    /**
     * @return string
     */
    protected abstract function getApiUrl();
}