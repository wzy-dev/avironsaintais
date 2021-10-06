<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @Vich\Uploadable 
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="article_images", fileNameProperty="image")
     * @var File
     * @Assert\Image(
     *     maxSize = "8000000",
     *     mimeTypesMessage = "Votre fichier n'est pas une photo",
     *     maxSizeMessage = "Votre fichier est trop gros ({{ size }}). La taille maximum autorisée est : {{ limit }}"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()        
     */
    private $alt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Article", mappedBy="image", cascade={"persist"})
     */
    private $article;

    public function getId()
    {
        return $this->id;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * @ORM\PreRemove
     */
    public function detachImage()
    {
        if ($this->article) {
            $this->article->setImage(null);
        }
    }
    
    public function getAbsolutePath()
    {
        return 'uploads/articles/'.$this->getImage();
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        // set (or unset) the owning side of the relation if necessary
        $newImage = $article === null ? null : $this;
        if ($newImage !== $article->getImage()) {
            $article->setImage($newImage);
        }

        return $this;
    }
}
