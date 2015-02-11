<?php

namespace Acme\LogEntryBundle\Listener;

use Gedmo\Loggable\LoggableListener as LoggableListener;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;
use Gedmo\Tool\Wrapper\AbstractWrapper;

/**
 * Overriding LoggableListener,
 * to log changed data
 */
class LoggerListener extends LoggableListener
{

    /**
     * Create a new Log instance
     *
     * @param string $action
     * @param object $object
     * @param LoggableAdapter $ea
     * @return void
     */
    protected function createLogEntry($action, $object, LoggableAdapter $ea)
    {
        $om = $ea->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $om);
        $meta = $wrapped->getMetadata();
        if ($config = $this->getConfiguration($om, $meta->name)) {
            $logEntryClass = $this->getLogEntryClass($ea, $meta->name);
            $logEntryMeta = $om->getClassMetadata($logEntryClass);
            /** @var \Gedmo\Loggable\Entity\LogEntry $logEntry */
            $logEntry = $logEntryMeta->newInstance();

            $logEntry->setAction($action);
            $logEntry->setUsername($this->username);
            $logEntry->setObjectClass($meta->name);
            $logEntry->setLoggedAt();

            // check for the availability of the primary key
            $uow = $om->getUnitOfWork();
            if ($action === self::ACTION_CREATE && $ea->isPostInsertGenerator($meta)) {
                $this->pendingLogEntryInserts[spl_object_hash($object)] = $logEntry;
            } else {
                $logEntry->setObjectId($wrapped->getIdentifier());
            }
            $newValues = array();
            $oldValues = array();
            if ($action !== self::ACTION_REMOVE && isset($config['versioned'])) {
                foreach ($ea->getObjectChangeSet($uow, $object) as $field => $changes) {
                    // var_dump($changes);exit;
                    if (!in_array($field, $config['versioned'])) {
                        continue;
                    }
                    $value = $changes[1];
                    $oldValue = $changes[0];
                    if ($meta->isSingleValuedAssociation($field) && $value) {
                        $oid = spl_object_hash($value);
                        $wrappedAssoc = AbstractWrapper::wrap($value, $om);
                        $value = $wrappedAssoc->getIdentifier(false);
                        if (!is_array($value) && !$value) {
                            $this->pendingRelatedObjects[$oid][] = array(
                                'log' => $logEntry,
                                'field' => $field
                            );
                        }
                    }
                    $newValues[$field] = $value;
                    $oldValues[$field] = $oldValue;
                }
                $logEntry->setData($newValues);
            }

            if (!($action === self::ACTION_CREATE)) {
                $logEntry->setBeforeData($oldValues);
            }
            $user = $om->getRepository('AcmeUserBundle:User')->findOneByUsername($this->username);
            $logEntry->setUser($user);
            $logEntry->setObjectName($object->getObjectName());

            if($action === self::ACTION_UPDATE && 0 === count($newValues)) {
                return;
            }

            $version = 1;
            if ($action !== self::ACTION_CREATE) {
                $version = $ea->getNewVersion($logEntryMeta, $object);
                if (empty($version)) {
                    // was versioned later
                    $version = 1;
                }
            }
            $logEntry->setVersion($version);

            $this->prePersistLogEntry($logEntry, $object);

            $om->persist($logEntry);
            $uow->computeChangeSet($logEntryMeta, $logEntry);
        }
    }

}
