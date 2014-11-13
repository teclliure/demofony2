<?php

namespace  Demofony2\CoreBundle\Entity\Traits;

trait DocumentTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $documentName;

    protected $removeDocument;

    /**
     * Set document_name
     *
     * @param string $documentName
     */
    public function setDocumentName($documentName)
    {
        $this->documentName = $documentName;
    }

    /**
     * Get document_name
     *
     * @return string
     */
    public function getDocumentName()
    {
        return $this->documentName;
    }

    public function setDocument($file)
    {
        $this->document = $file;
        $this->updatedAt = new \DateTime();
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function getRemoveDocument()
    {
        return $this->removeDocument;
    }

    public function setRemoveDocument($removeDocument)
    {
        $this->removeDocument = $removeDocument;
    }
}
