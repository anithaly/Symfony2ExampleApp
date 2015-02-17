<?php

namespace Acme\LogEntryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LogController extends Controller
{
    /**
     * @Route("/log", name="log")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $logs = $em->getRepository('AcmeLogEntryBundle:CustomLogEntry')->findBy([], ['id' => 'desc']);
        return array('logs' => $logs);
    }
}
