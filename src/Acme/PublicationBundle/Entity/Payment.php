<?php

namespace Acme\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AntQa\Bundle\PayUBundle\Model\Payment as PaymentModel;

/**
 * Class Payment
 *
 * @ORM\Entity()
 * @ORM\Table(name="payments")
 */
class Payment extends PaymentModel
{

}