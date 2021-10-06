<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 * @Vich\Uploadable 
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)    
     * @Assert\NotBlank(
     *      message = "Vous devez entrer un nom"
     * )   
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(
     *      message = "Vous devez entrer une url valide"
     * )
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="partenaire_images", fileNameProperty="image")
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
     * @Assert\NotNull(
     *     message = "Veuillez charger une photo"
     * )          
     */
    private $imageData;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
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
        $this->imageData = $image;        
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
    
    public function getAbsolutePath()
    {
        return 'uploads/partenaires/'.$this->getImage();
    }

    public function getImageData(): ?string
    {
        return $this->imageData;
    }

    public function setImageData(string $imageData): self
    {
        $this->imageData = $imageData;

        return $this;
    }
}
