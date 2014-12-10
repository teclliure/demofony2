<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Document;
use Demofony2\AppBundle\Entity\Image;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
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
     * @param RequestStack        $request
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
        $path = $this->uploadHelper->asset($document, 'participation_document');
        $url = $this->request->getUriForPath($path);

        return $url;
    }

    /**
     * @param Image $image
     *
     * @return string
     */
    public function getImageUrl(Image $image)
    {
        $path = $this->uploadHelper->asset($image, 'participation_image');
        $url = $this->request->getUriForPath($path);

        return $url;
    }
}
