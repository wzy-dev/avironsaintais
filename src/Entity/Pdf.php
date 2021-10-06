<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PdfRepository")
 * @Vich\Uploadable
 */
class Pdf
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $pdf;

    /**
     * @Vich\UploadableField(mapping="article_pdfs", fileNameProperty="pdf")
     * @var File
     * @Assert\File(
     *     maxSize = "64000000",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Votre fichier n'est pas un pdf",
     *     maxSizeMessage = "Votre fichier est trop gros ({{ size }}Mo). La taille maximum autorisée est : {{ limit }}Mo"
     * )
     */
    private $pdfFile;

    public function getId()
    {
        return $this->id;
    }

    public function setPdfFile(File $pdf = null)
    {
        $this->pdfFile = $pdf;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($pdf) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPdf()
    {
        return $this->pdf;
    }

    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }
    
    public function getAbsolutePath()
    {
        return 'uploads/articles/pdfs/'.$this->getPdf();
    }
}
