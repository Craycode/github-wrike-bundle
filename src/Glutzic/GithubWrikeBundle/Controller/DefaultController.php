<?php

namespace Glutzic\GithubWrikeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GlutzicGithubWrikeBundle:Default:index.html.twig', array('name' => $name));
    }
}
