<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message = "Vous devez entrer un texte"
     * )
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Votre texte doit comporter au moins {{ limit }} charactères",
     * )     
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Vous devez entrer un titre"
     * )
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Votre titre doit comporter au moins {{ limit }} charactères",
     * )     
     */
    private $title;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", inversedBy="article", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Pdf", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="article_pdfs",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pdf", referencedColumnName="id", unique=true)}
     * )
     * @Assert\Valid
     */
    private $pdfs;

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->pdfs = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;
        if ($image) {
            $image->setAlt($this->title);
        }

        return $this;
    }

    /**
     * @return Collection|Pdf[]
     */
    public function getPdfs(): Collection
    {
        return $this->pdfs;
    }

    public function addPdf(Pdf $pdf): self
    {
        if (!$this->pdfs->contains($pdf)) {
            $this->pdfs[] = $pdf;
        }

        return $this;
    }

    public function removePdf(Pdf $pdf): self
    {
        if ($this->pdfs->contains($pdf)) {
            $this->pdfs->removeElement($pdf);
        }

        return $this;
    }
}
