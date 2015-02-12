<?php

namespace Acme\PublicationBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\PublicationBundle\Entity\Publication;

class PublicationListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function preRemove(Publication $publication, LifecycleEventArgs $event)
    {
        $securityContext = $this->container->get('security.context');

        if (null === $securityContext) {
            return;
        }

        $token = $securityContext->getToken();
        if (null !== $token && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $entityManager = $event->getObjectManager();
            $publication->setDeletedBy($token->getUser());
            $entityManager->persist($publication);
            $entityManager->flush();
        }
    }

}
