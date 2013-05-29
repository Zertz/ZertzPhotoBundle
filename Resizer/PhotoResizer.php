<?php
namespace Zertz\PhotoBundle\Resizer;

class PhotoResizer
{
    private $imageType;
    
    protected $quality;
    
    public function setQuality($quality)
    {
        $this->quality = $quality;
        
        return $this;
    }
    
    public function getQuality()
    {
        return $this->quality;
    }

    public function resize($filename, $width, $height)
    {
        if ($width <= 0 && $height <= 0) {
            throw new Exception('At least one of $width or $height must be greater than 0');
        }
        
        $image = $this->load($filename);
        
        $srcWidth = $this->getWidth($image);
        $srcHeight = $this->getHeight($image);
        $srcOffsetX = 0;
        $srcOffsetY = 0;
        
        $srcRatio = $srcWidth / $srcHeight;
        
        if ($width == 0) {
            $width = $this->getProportionalWidth($image, $height);
        } else if ($height == 0) {
            $height = $this->getProportionalHeight($image, $width);
        } else {
            $newRatio = $width / $height;
            
            if ($srcRatio < $newRatio) {
                $srcNewHeight = ($srcWidth / $width) * $height;
                $srcOffsetY = ($srcHeight - $srcNewHeight) / 2;
                $srcHeight = $srcNewHeight;
            } else {
                $srcNewWidth = ($srcHeight / $height) * $width;
                $srcOffsetX = ($srcWidth - $srcNewWidth) / 2;
                $srcWidth = $srcNewWidth;
            }
        }
        
        $newImage = imagecreatetruecolor($width, $height);
        
        /* Handle transparency
        if ($this->imageType == IMAGETYPE_GIF || $this->imageType == IMAGETYPE_PNG) {
            $current_transparent = imagecolortransparent($this->image);
            if ($current_transparent != -1) {
                $transparent_color = imagecolorsforindex($this->image, $current_transparent);
                $current_transparent = imagecolorallocate($newImage, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($newImage, 0, 0, $current_transparent);
                imagecolortransparent($newImage, $current_transparent);
            } else if ($this->imageType == IMAGETYPE_PNG) {
                imagealphablending($newImage, false);
                $color = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
                imagefill($newImage, 0, 0, $color);
                imagesavealpha($newImage, true);
            }
        }*/
        
        imagecopyresampled($newImage, $image, 0, 0, $srcOffsetX, $srcOffsetY, $width, $height, $srcWidth, $srcHeight);
        
        $this->save($filename, $newImage);
    }

    protected function load($filename)
    {
        $image_info = getimagesize($filename);
        $this->imageType = $image_info[2];

        if ($this->imageType == IMAGETYPE_JPEG) {
           return imagecreatefromjpeg($filename);
        } else if ($this->imageType == IMAGETYPE_GIF) {
           return imagecreatefromgif($filename);
        } else if ($this->imageType == IMAGETYPE_PNG ) {
           return imagecreatefrompng($filename);
        }
    }

    protected function save($filename, $image)
    {
        if ($this->imageType == IMAGETYPE_JPEG) {
            imagejpeg($image, $filename, $this->quality);
        } else if ($this->imageType == IMAGETYPE_GIF) {
            imagegif($image, $filename);
        } else if ($this->imageType == IMAGETYPE_PNG) {
            imagepng($image, $filename);
        }
    }

    protected function getWidth($image)
    {
        return imagesx($image);
    }

    protected function getHeight($image)
    {
        return imagesy($image);
    }
    
    protected function getProportionalWidth($image, $height)
    {
        return $this->getWidth($image) * ($height / $this->getHeight($image));
    }
    
    protected function getProportionalHeight($image, $width)
    {
        return $this->getheight($image) * ($width / $this->getWidth($image));
    }
}
