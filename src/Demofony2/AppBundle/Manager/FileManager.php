<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Document;
use Demofony2\AppBundle\Entity\Image;
use Demofony2\UserBundle\Entity\User;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class FileManager
{
    protected $uploadHelper;
    protected $imagineCache;
    protected $request;

    /**
     * @param UploaderHelper $uploadHelper
     * @param CacheManager   $imagineCache
     * @param RequestStack   $request
     */
    public function __construct(UploaderHelper $uploadHelper, CacheManager $imagineCache, RequestStack $request)
    {
        $this->uploadHelper = $uploadHelper;
        $this->imagineCache = $imagineCache;
        $this->request = $request->getCurrentRequest();
    }

    /**
     * @param Document $document
     *
     * @return string
     */
    public function getDocumentUrl(Document $document)
    {
        $path = $this->uploadHelper->asset($document, 'document');

        if (is_object($this->request)) {
            $url = $this->request->getUriForPath($path);

            return $url;
        }

        return $path;
    }

    /**
     * @param Image  $image
     * @param string $type
     *
     * @return string
     */
    public function getImageUrl(Image $image, $type = 'small')
    {
        $path = $this->uploadHelper->asset($image, 'image');
        $profileImage = $this->imagineCache->generateUrl($path, $type);

        return $profileImage;
    }

    /**
     * @param User   $user
     * @param string $type
     *
     * @return string
     */
    public function getUserImageUrl(User $user, $type = 'small')
    {
        // print_r ($user); exit();
        $path = $user->getWebPath();
        if (null === $user->getImage()) {
            if ($this->request) {
                return $this->request->getSchemeAndHttpHost().$this->request->getBasePath().'/'.$this->getDefaultUserProfileImage();
            }
            else return null;
        }

        $profileImage = $this->imagineCache->generateUrl($path, $type);

        return $profileImage;
    }

    /**
     * @param User   $user
     * @param string $type
     *
     * @return string
     */
    public function getDefaultUserProfileImage()
    {
        return 'bundles/demofony2app/images/profile_default.png';
    }
}
