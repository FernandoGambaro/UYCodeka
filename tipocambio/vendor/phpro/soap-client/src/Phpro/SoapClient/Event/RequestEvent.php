<?php

namespace Phpro\SoapClient\Event;

use Phpro\SoapClient\Client;
use Phpro\SoapClient\Type\RequestInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class RequestEvent
 *
 * @package Phpro\SoapClient\Event
 */
class RequestEvent extends Event
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param Client $client
     * @param string $method
     * @param RequestInterface $request
     */
    public function __construct(Client $client, $method, RequestInterface $request)
    {
        $this->client = $client;
        $this->method = $method;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
