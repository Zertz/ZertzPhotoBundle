<?php
namespace Zertz\PhotoBundle\File;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Zertz\PhotoBundle\Resizer\PhotoResizer;

class FileManager
{
    protected $rootDirectory;
    
    protected $domain;
    
    protected $formats;
    
    protected $quality;
    
    public function __construct(ContainerInterface $container) {
        $this->domain = $container->getParameter('zertz.domain');
        $this->rootDirectory = $container->getParameter('zertz.directory');
        
        $this->formats = $container->getParameter('zertz.formats');
        $this->quality = $container->getParameter('zertz.quality');
    }
    
    public function saveUpload(UploadedFile $file, $filename)
    {
        $fs = new Filesystem();
        $ps = new PhotoResizer();
        
        $file->move($this->getAbsolutePath($filename), $filename);
        
        $directory = $this->getAbsolutePath($filename);
        $filepath = '';
        
        foreach ($this->formats as $key => $format) {
            $filepath = $directory . $key . '/' . $filename;
            
            if (!$fs->exists($directory . $key)) {
                $fs->mkdir($directory . $key, 0755);
            }
            
            $fs->copy($directory . $filename, $filepath, true);
            
            if ($fs->exists($filepath)) {
                if (isset($format['quality'])) {
                    $ps->setQuality($format['quality']);
                } else {
                    $ps->setQuality($this->quality);
                }
                
                $ps->resize($filepath, $format['size']['width'], $format['size']['height']);
            }
        }
    }
    
    public function removeUpload($filename)
    {
        $fs = new Filesystem();
        
        $directory = $this->getAbsolutePath($filename);
        
        if ($fs->exists($directory . $filename)) {
            unlink($directory . $filename);
        }
        
        foreach ($this->formats as $key => $format) {
            if ($fs->exists($directory . $key . '/' . $filename)) {
                unlink($directory . $key . '/' . $filename);
            }
        }
    }
    
    protected function getAbsolutePath($filename)
    {
        return $this->rootDirectory . $this->getUploadDir($filename);
    }
    
    public function getWebPath($filename, $format)
    {
        if (!empty($filename) && array_key_exists($format, $this->formats)) {
            return $this->domain . $this->getUploadDir($filename) . $format . '/' . $filename;
        }
        
        return null;
    }

    protected function getUploadDir($filename)
    {
        return '/' . substr($filename, 0, 1) . '/' . substr($filename, 1, 1) . '/';
    }
}
