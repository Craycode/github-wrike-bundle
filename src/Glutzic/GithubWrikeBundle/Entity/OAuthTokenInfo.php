<?php

namespace Glutzic\GithubWrikeBundle\Entity;

class OAuthTokenInfo implements \Serializable
{
    protected $token;
    protected $secret;

    public function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function serialize()
    {
        return serialize(array('token' => $this->token, 'secret' => $this->secret));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->token = $data['token'];
        $this->secret = $data['secret'];
    }

}
