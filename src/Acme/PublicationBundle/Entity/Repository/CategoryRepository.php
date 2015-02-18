<?php

namespace Acme\PublicationBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{

    // todo create repository to extend with that method

    public function findAllDeleted()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('l')
            ->from('AcmePublicationBundle:Category', 'l')
            ->where(
                $qb->expr()->isNotNull('l.deletedAt')
            )
            ->orderBy('l.id', 'desc')
            ;
        return $qb->getQuery()->getResult();
    }

}
