<?php
namespace Zertz\PhotoBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PhotoInterface
{
    /**
     * Sets the file name
     *
     * @param string $filename
     * 
     * @return self
     */
    public function setFileName($filename);

    /**
     * Gets the file name
     *
     * @return string 
     */
    public function getFileName();
    
    /**
     * Gets the creation time
     * 
     * @return \DateTime
     */
    public function setCreatedAt();
    
    /**
     * Sets the creation time
     * 
     * @return \DateTime
     */
    public function getCreatedAt();
    
    /**
     * Sets the last updated time
     * 
     * @return \DateTime
     */
    public function setUpdatedAt();
    
    /**
     * Gets the last updated time
     * 
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Sets the file
     *
     * @param UploadedFile $file
     * 
     * @return self
     */
    public function setFile(UploadedFile $file = null);

    /**
     * Gets the file
     *
     * @return UploadedFile
     */
    public function getFile();
    
    /**
     * Sets the old file name
     * 
     * @param type $oldFilename
     * 
     * @return self
     */
    public function setOldFilename($oldFilename);
    
    /**
     * Gets the old file name
     * 
     * @return string
     */
    public function getOldFilename();
}
