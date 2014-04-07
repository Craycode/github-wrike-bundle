<?php

namespace Glutzic\GithubWrikeBundle\Adapter;

use Github\Client as GithubClient;
use Symfony\Component\HttpFoundation\Session\Session;

class GithubAdapter
{
    /**
     *
     * @var GithubClient
     */
    protected $client;

    /**
     *
     * @var Session
     */
    protected $session;

    public function __construct(GithubClient $client, Session $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    /**
     *
     * @return \Github\Api\User
     */
    public function userApi()
    {
        return $this->getClient()->api('user');
    }

    /**
     *
     * @return \Github\Api\Issue
     */
    public function issuesApi()
    {
        return $this->getClient()->api('issues');
    }

    /**
     *
     * @return GithubClient
     */
    public function getClient()
    {
        return $this->client;
    }

}
