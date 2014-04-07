<?php

namespace Glutzic\GithubWrikeBundle\Adapter;

use Glutzic\GithubWrikeBundle\Client\WrikeClient;
use Symfony\Component\HttpFoundation\Session\Session;
use Glutzic\GithubWrikeBundle\Entity\OAuthTokenInfo;

class WrikeAdapter
{
    /**
     *
     * @var WrikeClient
     */
    protected $client;

    /**
     *
     * @var Session
     */
    protected $session;

    public function __construct(WrikeClient $client, Session $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    private function urlencode_rfc3986($string)
    {
        return str_replace("%7E", "~", rawurlencode($string));
    }

    public function getRequestToken()
    {
        $tokenInfo = $this->getClient()->getRequestToken();
        $this->session->set('requestTokenInfo', new OAuthTokenInfo($tokenInfo['token'], $tokenInfo['secret']));
    }

    public function getAccessToken($token)
    {
        $accessTokenInfo = $this->getClient()->getAccessToken($token, $this->session->get('requestTokenInfo')->getSecret());
        $tokenInfo = new OAuthTokenInfo($accessTokenInfo['token'], $accessTokenInfo['secret']);
        $this->session->set('accessTokenInfo', $tokenInfo);
        $this->client->setAccessInfo($tokenInfo);
    }

    public function getAuthorisationUrl($callback)
    {
        $tokenInfo = $this->session->get('requestTokenInfo');
        return "https://www.wrike.com/rest/auth/authorize?oauth_token=".$tokenInfo->getToken()."&oauth_callback=".$this->urlencode_rfc3986($callback);
    }

    public function hasGrantedAccess()
    {
        return $this->session->has('accessTokenInfo');
    }

    /**
     *
     * @return WrikeClient
     */
    public function getClient()
    {
        return $this->client;
    }

}
