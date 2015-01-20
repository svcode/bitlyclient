<?php

namespace BitlyClient;

use GuzzleHttp\Client as GuzzleClient;

class Client 
{
    protected $bitlyService;

    protected $token;

    protected $queryOptions = array();

    protected $url;

    protected $baseUrl = 'https://api-ssl.bitly.com';

    public function __construct($token) 
    {
        $this->token = $token;
        $this->bitlyService = new GuzzleClient(['base_url' => $this->baseUrl]);
    }

    public function getResponse() 
    {
        $options = array(
            'access_token' => $this->token
        );
        $options = array_merge($options, $this->queryOptions);

        $url = $this->baseUrl . $this->url . '?' . http_build_query($options);
        $response = $this->bitlyService->get($url);

        if ($response->getStatusCode() != 200 || $response->getReasonPhrase() != 'OK') {
            throw new \Exception('There is error to get Bitly service data.');
        }

        $result = $response->json();

        return isset($result['data']) ? $result['data'] : array();
    }

    public function linkInfo($link, $options = array())
    {
        $this->url = '/v3/link/info';
        $this->queryOptions = $options;
        $this->queryOptions['link'] = $link;

        return $this->getResponse();
    }
}
