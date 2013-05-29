<?php

namespace Zertz\PhotoBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use Zertz\PhotoBundle\Model\PhotoInterface;

class Photo implements PhotoInterface
{
    protected $filename;
    
    protected $createdAt;
    
    protected $updatedAt;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
    private $oldFilename;
    
    public function __construct() {
        
    }

    /**
     * Set file name
     *
     * @param string $filename
     * @return self
     */
    public function setFileName($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get file name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->filename;
    }
    
    /**
     * 
     * 
     * return self
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime('now');
        
        return $this;
    }
    
    /**
     * 
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * 
     * 
     * return self
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime('now');
        
        return $this;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Sets file
     *
     * @param UploadedFile $file
     * @return self
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        
        if ($file !== null && isset($this->filename)) {
            $this->oldFilename = $this->filename;
            // Trigger preUpdate/postUpdate
            $this->updatedAt = new \DateTime('now');
        }
        
        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Set old file name
     * 
     * @param type $oldFilename
     * @return self
     */
    public function setOldFilename($oldFilename)
    {
        $this->oldFilename = $oldFilename;
        
        return $this;
    }
    
    /**
     * Get old file name
     * 
     * @return string
     */
    public function getOldFilename()
    {
        return $this->oldFilename;
    }
}