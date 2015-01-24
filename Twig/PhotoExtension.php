<?php
namespace Zertz\PhotoBundle\Twig;

use Zertz\PhotoBundle\File\FileManager;

class PhotoExtension extends \Twig_Extension
{
    protected $fileManager;
    
    public function __construct(FileManager $fileManager) {
        $this->fileManager = $fileManager;
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('path', array($this, 'webPathFilter')),
        );
    }

    public function webPathFilter($filename, $format)
    {
        return $this->fileManager->getWebPath($filename, $format);
    }

    public function getName()
    {
        return 'photo_extension';
    }
}
