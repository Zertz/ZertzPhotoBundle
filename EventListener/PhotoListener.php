<?php
namespace Zertz\PhotoBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Zertz\PhotoBundle\Entity\Photo;
use Zertz\PhotoBundle\File\FileManager;

class PhotoListener
{
    protected $fileManager;
    
    public function __construct(FileManager $fileManager) {
        $this->fileManager = $fileManager;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->handlePreEvent($args);
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->handlePreEvent($args);
    }
    
    protected function handlePreEvent(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if ($this->isPhoto($entity)) {
            if ($this->isPhoto($entity) && null !== $entity->getFile()) {
                $entity->setFilename(sha1_file($entity->getFile()) . '.' . $entity->getFile()->guessExtension());
            }

            if ($args instanceof PreUpdateEventArgs) {
                $entity->setUpdatedAt();

                $em = $args->getEntityManager();
                $cm = $em->getClassMetadata(get_class($entity));
                $em->getUnitOfWork()->recomputeSingleEntityChangeSet($cm, $entity);
            } else {
                $entity->setCreatedAt();
            }
        }
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->post($args);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->post($args);
    }
    
    protected function post(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if ($this->isPhoto($entity) && null !== $entity->getFile()) {
            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
            $this->fileManager->saveUpload($entity->getFile(), $entity->getFilename());

            if ($entity->getOldFilename()) {
                $this->fileManager->removeUpload($entity->getOldFilename());
            }

            $entity->setFile(null);
        }
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        if ($this->isPhoto($args->getEntity())) {
            $this->fileManager->removeUpload($args->getEntity()->getFilename());
        }
    }
    
    protected function isPhoto($entity)
    {
        return $entity instanceof Photo;
    }
}
