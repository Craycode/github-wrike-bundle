<?php

namespace Glutzic\GithubWrikeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Client;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
     *
     * @return \Glutzic\GithubWrikeBundle\Adapter\GithubAdapter
     */
    protected function getGithubAdapter()
    {
        return $this->get('glutzic_github_wrike.github_adapter');
    }

    /**
     * 
     * @return \Glutzic\GithubWrikeBundle\Adapter\WrikeAdapter
     */
    protected function getWrikeAdapter()
    {
        return $this->get('glutzic_github_wrike.wrike_adapter');
    }

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return $this->get('session');
    }

    public function accessAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $token = $request->get('oauth_token');

        if (!$token) {
            throw $this->createNotFoundException('Request token could not be obtained');
        }

        $this->getWrikeAdapter()->getAccessToken($token);

        if ($this->getWrikeAdapter()->hasGrantedAccess()) {
            return new \Symfony\Component\HttpFoundation\Response('Access granted');
        }

        throw $this->createNotFoundException('Unable to get access token');
    }

    public function authoriseAction()
    {
        $adapter = $this->getWrikeAdapter();

        if ($adapter->hasGrantedAccess()) {
            return new \Symfony\Component\HttpFoundation\Response('Wrike access has already been granted.');
        }

        $callbackUrl = $this->generateUrl('glutzic_github_wrike_access', array(), UrlGeneratorInterface::ABSOLUTE_URL);
        $adapter->getRequestToken();

        return $this->redirect($adapter->getAuthorisationUrl($callbackUrl));
    }

    public function executeAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $adapter = $this->getWrikeAdapter();

        if (!$adapter->hasGrantedAccess()) {
            return $this->redirect($this->generateUrl('glutzic_github_wrike_authorise'));
        }

        // Execute
        $adapter->getClient()->setAccessInfo($this->getSession()->get('accessTokenInfo'));
        $profile = $adapter->getClient()->addTask('test');

        return $this->render('GlutzicGithubWrikeBundle:Default:index.html.twig', array('tokenInfo' => array()));
    }

}
