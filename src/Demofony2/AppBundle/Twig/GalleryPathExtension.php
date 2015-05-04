<?php

/**
 * Demofony2 for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 19/03/15
 * Time: 12:35
 */
namespace Demofony2\AppBundle\Twig;

class GalleryPathExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('gallery', array($this, 'galleryFilter')),
        );
    }

    public function galleryFilter($imageName, $object)
    {
        $path = $object->getRealPath($imageName);

        return $path;
    }

    public function getName()
    {
        return 'app_gallery_extension';
    }
}
