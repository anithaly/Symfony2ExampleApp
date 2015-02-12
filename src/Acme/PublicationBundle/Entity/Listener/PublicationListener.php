<?php

namespace Acme\PublicationBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Acme\PublicationBundle\Entity\Publication;

class PublicationListener
{
    /**
     * @var TokenStorageInterface
     */
    private $token_storage;

    public function __construct(TokenStorageInterface $token_storage)
    {
        $this->token_storage = $token_storage;
    }

    public function preRemove(Publication $publication, LifecycleEventArgs $event)
    {
        $token = $this->token_storage->getToken();
        if (null !== $token) {
            $entityManager = $event->getObjectManager();
            $publication->setDeletedBy($token->getUser());
            $entityManager->persist($publication);
            $entityManager->flush();
        }
    }

}
