<?php

namespace Acme\LogEntryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Acme\LogEntryBundle\Entity\CustomLogEntry;

/**
 * @Route("/log")
 */
class LogController extends Controller
{
    /**
     * @Route("/", name="log")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $logs = $em->getRepository('AcmeLogEntryBundle:CustomLogEntry')->findBy([], ['id' => 'desc']);
        return array('logs' => $logs);
    }

    /**
     * Revert logged object to log version
     * @Route("/revert/{id}", name="log_revert")
     * @ParamConverter("log", class="AcmeLogEntryBundle:CustomLogEntry")
     */
    public function revertAction(CustomLogEntry $log)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AcmeLogEntryBundle:CustomLogEntry');
        $object = $em->find($log->getObjectClass(), $log->getObjectId());

        if (!$object) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $repo->revert($object, $log->getVersion()); // relation would be reverted also
        $em->persist($object);
        $em->flush();

        $em = $this->getDoctrine()->getManager();

        return $this->redirect($this->generateUrl('log'));
    }

    /**
     * Undelete object
     * @Route("/restore/{id}", name="log_restore")
     * @ParamConverter("log", class="AcmeLogEntryBundle:CustomLogEntry")
     */
    public function undeleteAction(CustomLogEntry $log)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');

        $repo = $em->getRepository('AcmeLogEntryBundle:CustomLogEntry');
        $object = $em->find($log->getObjectClass(), $log->getObjectId());

        if (!$object) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        if (!$object->getDeletedAt()) {
            throw $this->createNotFoundException('Entity already restored.');
        }

        $object->setDeletedAt(NULL);
        $em->persist($object);
        $em->flush();

        return $this->redirect($this->generateUrl('log'));
    }
}
