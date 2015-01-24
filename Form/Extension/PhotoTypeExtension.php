<?php
namespace Zertz\PhotoBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Zertz\PhotoBundle\File\FileManager;

class PhotoTypeExtension extends AbstractTypeExtension
{
    protected $fileManager;
    
    public function __construct(FileManager $fileManager) {
        $this->fileManager = $fileManager;
    }
    
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'file';
    }
    
    /**
     * Add the webPath option
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'format' => null,
        ));
    }
    
    /**
     * Pass the image url to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('format', $options)) {
            $parentData = $form->getParent()->getData();
            
            if (null !== $parentData) {
                $webPath = $this->fileManager->getWebPath(
                    $parentData->getFilename(),
                    $options['format']
                );
            } else {
                 $webPath = null;
            }
            
            $view->vars['webPath'] = $webPath;
        }
    }
}
