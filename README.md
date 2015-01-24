ZertzPhotoBundle
========================

This bundle provides an easy to use platform to automatically resize photos on
upload and display them back to the user.

### Features
- Photo management
- Customizable thumbnails
- Resizing

### Upcoming features
- Batch resize from the command-line

1) Requirements
----------------------------------

1. Symfony 2.2 **Although it wasn't tested, it should also work with 2.1**
2. Doctrine 2

2) Installation
----------------------------------

In composer.json, add:

    "require": {
        "zertz/photo-bundle": "dev-master"
    }

Run an update to install the bundle:

    php composer.phar update zertz/photo-bundle

3) Configuration
----------------------------------

### AppKernel.php

Enable the bundle:

    public function registerBundles()
    {
        $bundles = array(
            new Zertz\PhotoBundle\ZertzPhotoBundle(),
        );
    }

### config.yml

Add the following lines:

    # Twig Configuration
    twig:
        form:
            resources:
                - ZertzPhotoBundle:Form:fields.html.twig

    # ZertzPhotoBundle Configuration
    zertz_photo:
        directory: /path/to/save/to
        domain: http://img.yourdomain.com
        quality: 70
        formats:
            small:
                size: { width: 140 , height: 87 }
            medium:
                size: { height: 175 }
            large:
                size: { width: 635 }
                quality: 90

**Note**

> `directory` and `domain` keys are **required**. For JPEG photos, the `quality`
> setting is customizable, but **defaults** to 70.

**Formats**

> `formats` are customizable and the `size` property may contain one of, or
> both, `width` and `height`. The photo will automatically be resized and the
> aspect ratio is always maintained. For JPEG photos, the **optional** `quality`
> setting overrides the global `quality` setting.

> If no `formats` are defined, then the original photo is simply uploaded.

### Extend the Photo class

This bundle provides the basics for persisting a photo object to the database. 
It is your role however to extend the `Photo` class and add any fields you deem
useful.

To get started, your entity class should look like this:

    <?php
    // src/Acme/PhotoBundle/Entity/Photo.php
    
    namespace Acme\PhotoBundle\Entity;
    
    use Doctrine\ORM\Mapping as ORM;
    use Zertz\PhotoBundle\Entity\Photo as BasePhoto;

    /**
     * @ORM\Entity
     * @ORM\Table(name="zertz_photo")
     */
    class Photo extends BasePhoto
    {
        /**
         * @ORM\Column(name="ID", type="integer", nullable=false)
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        public function __construct() {
            parent::__construct();
        }

        /**
         * Get id
         *
         * @return integer 
         */
        public function getId()
        {
            return $this->id;
        }
    }

Finally, run the following command to update the database schema:

    php app/console doctrine:schema:update --force

4) Usage
----------------------------------

The bundle provides simple helpers to upload and display photos.

In a form, add the `format` option and the bundle will automatically take care
of the rendering:

    ->add('file', 'file', array(
        'format' => 'small',
    ))

In a Twig template, add the `path` filter and specify the format to display:

    <img src="{{ photo|path('medium') }}">

5) FAQ
----------------------------------

- _Why doesn't the photo preview appear in my form?_

If the photo doesn't appear at all, then the format request in your form doesn't
exist. Otherwise, if the format exists, you should regenerate thumbnails
manually using the following command:

    php app/console zertz:photo:generate myformat

or

    php app/console zertz:photo:generate --all
