<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema()
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(
     *     property="id",
     *     type="int",
     * )
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @OA\Property(
     *     property="title",
     *     type="string",
     * )
     */
    private $title;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @OA\Property(
     *     property="author",
     *     type="string",
     * )
     */
    private $author;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @OA\Property(
     *     property="published",
     *     type="string",
     *     description="Year"
     * )
     */
    private $published;

    /**
     * @Assert\NotBlank
     * @Assert\Isbn
     * @ORM\Column(type="string", length=255)
     * @OA\Property(
     *     property="isbn",
     *     type="string",
     * )
     */
    private $isbn;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     * @OA\Property(
     *     property="price",
     *     type="float",
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @OA\Property(
     *     property="description",
     *     type="string",
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @OA\Property(
     *     property="img",
     *     type="string",
     *     description="Url of image"
     * )
     */
    private $img;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="boolean")
     * @OA\Property(
     *     property="name",
     *     type="bool",
     *     description="false - not available, true - available"
     * )
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublished(): ?string
    {
        return $this->published;
    }

    public function setPublished(string $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
